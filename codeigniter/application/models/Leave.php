<?php
namespace App\Models;

class Leave extends \CI_Model implements \Sourcefit\Domain\LeaveManagement\Leave
{
    public $id;
    public $employeeId;
    public $leaveDate;
    public $status;
    public $updatedBy;
    public $createdAt;
    public $updatedAt;

    public static function create($leaveData): Leave
    {
        $leave = new Leave();
        $leave->id = $leaveData->id ?? null;
        $leave->employeeId = $leaveData->employeeId ?? null;
        $leave->leaveDate = $leaveData->leaveDate ?? null;
        $leave->status = $leaveData->status ?? null;
        $leave->updatedBy = $leaveData->updatedBy ?? null;
        $leave->createdAt = $leaveData->createdAt ?? null;
        $leave->updatedAt = $leaveData->updatedAt ?? null;
        return $leave;
    }

    public function file(string $leaveId, string $employeeId, \DateTimeInterface $leaveDate): void
    {
        $this->db->insert('leaves', array(
            'id' => $leaveId,
            'employeeId' => $employeeId,
            'leaveDate' => date_format($leaveDate, 'Y-m-d'),
            'status' => 'PENDING'
        ));
    }

    public function deny(string $leaveId, string $deniedBy, \DateTimeInterface $deniedOn): void
    {
        $this->db->where('id', $leaveId);
        $this->db->update('leaves', array(
            'updatedBy' => $deniedBy,
            'updatedAt' => date_format($deniedOn, 'Y-m-d H:i:s.u'),
            "status" => 'DENIED'
        ));
    }

    public function approve(string $leaveId, string $approvedBy, \DateTimeInterface $approvedOn): void
    {
        $this->db->where('id', $leaveId);
        $this->db->update('leaves', array(
            'updatedBy' => $approvedBy,
            'updatedAt' => date_format($approvedOn, 'Y-m-d H:i:s.u'),
            "status" => 'APPROVED'
        ));
    }

    public function cancel(string $leaveId, string $cancelledBy, \DateTimeInterface $cancelledOn): void
    {
        $this->db->where('id', $leaveId);
        $this->db->update('leaves', array(
            'updatedBy' => $cancelledBy,
            'updatedAt' => date_format($cancelledOn, 'Y-m-d H:i:s.u'),
            "status" => 'CANCELLED'
        ));
    }

    public function findOne(array $params)
    {
        $this->db->where($params);
        $query = $this->db->get('leaves', 1);
        if (empty($query->result())) {
            return null;
        }

        return Leave::create($query->result()[0]);
    }

    public function findAllAndBuild(string $employeeId = null)
    {
        $this->db->select('l.*, concat(e1.firstName, " ", e1.lastName) as empName, concat(e2.firstName, " ", e2.lastName) as updater');
        $this->db->from('leaves l');
        $this->db->join('employees e1', 'l.employeeId = e1.id', 'left');
        $this->db->join('employees e2', 'l.updatedBy = e2.id', 'left');

        if (!is_null($employeeId)) {
            $this->db->where('employeeId', $employeeId);
        }

        $this->db->order_by('leaveDate', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
}
