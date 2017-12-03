<?php
/**
 * Created by PhpStorm.
 * User: humingtang
 * Date: 12/2/17
 * Time: 10:14 PM
 */

namespace AppBundle\Service\database\MySQL;

use AppBundle\Service\model\Appointment;

class AppointmentConnector extends DBConnector
{
    /**
     * returns all appointments as appointment object
     */
    function getAllAppointments()
    {
        $appointments = array();
        $sql = "SELECT * FROM patient_data pd LEFT OUTER JOIN appointment_userAppointment au ON pd.id=au.patientDataID 
                LEFT OUTER JOIN patient_scheduleuser ps ON ps.patientID = pd.patient_id
                WHERE data_type='APPOINTMENT'";
        $query = $this->pdo->prepare($sql);
        // use exec() because no results are returned
        $query->execute();
        while($row = $query->fetch())
        {
            //all data is in json format
            $appointmentDetail = json_decode($row['data'],true);

            $appointment = new Appointment();
            $appointment->id = $row['appAppointmentID'];
            $appointment->patientID = $row['scheduleUserID'];

            //now we only have the local provider id, so we need to get the remote provider id...
            $appointment->providerID = '';
            //need to figure out service ID logic
            $appointment->serviceID = '';
            $appointment->start = $appointmentDetail['apptstartdatetime'];
            $appointment->end = $appointmentDetail['apptstopdatetime'];;


            $appointments[] = $appointment;
        }

        return $appointments;
    }
}