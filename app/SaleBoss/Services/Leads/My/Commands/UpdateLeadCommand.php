<?php  namespace SaleBoss\Services\Leads\My\Commands; 
class UpdateLeadCommand {

	public $name;
	public $description;
	public $phone;
	public $priority;
	public $tag;
	public $remind_at;

	public function __construct(
		$name,
		$description,
		$phone,
		$priority,
		$tag,
		$remind_at,
        $status,
        $id,
        $user
	){
		$this->name = $name;
		$this->description = $description;
		$this->phone = $phone;
		$this->priority = $priority;
		$this->tag = $tag;
		$this->remind_at = $remind_at;
        $this->status = $status;
        $this->id = $id;
        $this->user = $user;
	}
} 
