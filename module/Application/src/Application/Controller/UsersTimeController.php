<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsersTimeController extends AbstractActionController
{
    public function indexAction()
    {
        $em = $this
            ->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        $dateFilter = date('Y-m-d H:i:s', strtotime($this->params()->fromRoute('date')));
        $userId = $this->params()->fromRoute('userId');
        $query = "SELECT u.id AS userId,
                       IF(GROUP_CONCAT(IF(DATE_FORMAT(ut.stopTime, '%H:%i:%s') IS NULL, 'Стоп', '') SEPARATOR '') = '', 'Старт', 'Стоп') AS `stop`,
       CONCAT('<span style=\"color: green;\"><b>Job (', SEC_TO_TIME(SUM(IF(DATE_FORMAT('" . $dateFilter . "', '%Y-%m-%d') = DATE_FORMAT(startTime, '%Y-%m-%d'), diffTime(startTime, IF(stopTime IS NULL OR stopTime = '0000-00-00' OR ABS(DATEDIFF(stopTime, startTime)) > 0,
            IF(DATE_FORMAT(NOW(), '%Y-%m-%d') = DATE_FORMAT(startTime, '%Y-%m-%d'),
               NOW(),
               CONCAT(DATE_FORMAT(startTime, '%Y-%m-%d'), ' 18:00:00')
              ),
            stopTime
           ), '" . $dateFilter . "'), 0))), ')</b><br>', GROUP_CONCAT(IF(DATE_FORMAT('" . $dateFilter . "', '%Y-%m-%d') = DATE_FORMAT(startTime, '%Y-%m-%d'),
                    CONCAT('s ', DATE_FORMAT(ut.startTime, '%H:%i:%s'), ' po ', IFNULL(DATE_FORMAT(ut.stopTime, '%H:%i:%s'), if(ABS(DATEDIFF(now(), startTime)) > 0, concat('<span style=\"color: red;\"><b>', 'no STOP</b></span>'), 'current moment')), ' (', TIMEDIFF(getStopTime(startTime, IF(stopTime IS NULL OR stopTime = '0000-00-00' OR ABS(DATEDIFF(stopTime, startTime)) > 0,
            IF(DATE_FORMAT(NOW(), '%Y-%m-%d') = DATE_FORMAT(startTime, '%Y-%m-%d'),
               NOW(),
               CONCAT(DATE_FORMAT(startTime, '%Y-%m-%d'), ' 18:00:00')
              ),
            stopTime
           ), '" . $dateFilter . "'), startTime), ')', if(ABS(DATEDIFF(stopTime, startTime)) > 0, concat('<br><span style=\"color: red;\"><b>', 'no STOP</b></span>'), ''), '<br>'),
                    '') SEPARATOR ''), '</span>') AS startStopTime,
       getRestTime(u.id, '" . $dateFilter . "') AS restTime,
       CONCAT(u.lastname, ' ', u.firstname) AS `name`,
       SEC_TO_TIME(SUM(IF(WEEKDAY(startTime) NOT IN(5, 6) and startTime is not null, diffTime(startTime, IF(stopTime IS NULL OR stopTime = '0000-00-00' OR ABS(DATEDIFF(stopTime, startTime)) > 0,
            IF(DATE_FORMAT(NOW(), '%Y-%m-%d') = DATE_FORMAT(startTime, '%Y-%m-%d'),
               NOW(),
               CONCAT(DATE_FORMAT(startTime, '%Y-%m-%d'), ' 18:00:00')
              ),
            stopTime
           ), '2013-07-17 00:00:00'), 0))) AS workedTimeWeekly,
       SEC_TO_TIME(SUM(IF(DATE_FORMAT('" . $dateFilter . "', '%Y-%m-%d') = DATE_FORMAT(startTime, '%Y-%m-%d'), diffTime(startTime, IF(stopTime IS NULL OR stopTime = '0000-00-00' OR ABS(DATEDIFF(stopTime, startTime)) > 0,
            IF(DATE_FORMAT(NOW(), '%Y-%m-%d') = DATE_FORMAT(startTime, '%Y-%m-%d'),
               NOW(),
               CONCAT(DATE_FORMAT(startTime, '%Y-%m-%d'), ' 18:00:00')
              ),
            stopTime
           ), '" . $dateFilter . "'), 0))) AS workedTimeDaily,
       SEC_TO_TIME(SUM(IF(WEEKDAY(startTime) IN(5, 6), diffTime(startTime, IF(stopTime IS NULL OR stopTime = '0000-00-00' OR ABS(DATEDIFF(stopTime, startTime)) > 0,
            IF(DATE_FORMAT(NOW(), '%Y-%m-%d') = DATE_FORMAT(startTime, '%Y-%m-%d'),
               NOW(),
               CONCAT(DATE_FORMAT(startTime, '%Y-%m-%d'), ' 18:00:00')
              ),
            stopTime
           ), '" . $dateFilter . "'), 0))) AS workedTimeHolidays
  FROM usertime AS ut
    INNER JOIN users AS u ON u.id = ut.user_id
    INNER JOIN users AS uActive ON uActive.id = " . $userId . "
  WHERE UNIX_TIMESTAMP(ut.startTime) >= UNIX_TIMESTAMP(DATE_FORMAT(DATE_ADD('" . $dateFilter . "', INTERVAL -WEEKDAY(NOW()) DAY), '%Y-%m-%d 00:00:01'))
    AND IF(uActive.admin, 1, u.id = uActive.id)
  GROUP BY u.id
  ORDER BY u.lastname, u.firstname";

        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute();

        $currentUser = $em->find('Application\Entity\Users', $userId);


        return new ViewModel(array(
            'times'       => $stmt->fetchAll(),
            'currentUser' => $currentUser
        ));
    }
}
