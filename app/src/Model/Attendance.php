<?php
declare(strict_types=1);

namespace Farol360\Ancora\Model;

class Attendance
{
    public $id;
    public $id_patient;
    public $id_professional;
    public $status;
    public $attendance_day;
    public $attendance_hour;
    public $description;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->id_patient = $data['id_patient'] ?? null;
        $this->id_professional = $data['id_professional'] ?? null;
        $this->status = $data['id_status'] ?? null;
        $this->attendance_day = $data['attendance_day'] ?? null;
        $this->attendance_hour = $data['attendance_hour'] ?? null;
        $this->description = $data['description'] ?? null;
    }
}
