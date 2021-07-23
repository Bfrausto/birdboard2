<?php
namespace App\Models;

trait RecordsActivity{
    public $oldAttributes = [];

    public static function bootRecordsActivity()
    {
        static::updating(function($model){
            $model->oldAttributes = $model->getOriginal();
        });

        $recordableEvents=['created','updated','deleted'];
        foreach($recordableEvents as $event){
            static::$event(function($model)use($event){
                if(class_basename($model)!=='project'){
                    $event="{$event}_".strtolower(class_basename($model));
                }
                $model->recordActivity($event);
            });
        }
    }
    public function recordActivity($description)
    {
        $this->activity()->create([
            'description' => $description,
            'changes'=>$this->activityChanges(),
            'project_id' => class_basename($this)==='Project'?$this->id:$this->project_id

        ]);

        // Activity::create([
        //     'project_id'=>$this->id,
        //     'description'=>$type
        // ]);
    }
    public function activity()
    {
        return $this->morphMany(Activity::class,'subject')->latest();
    }
    public function activityChanges()
    {
        if($this->wasChanged()){
            return[
                'before' => array_except(array_diff($this->oldAttributes,$this->getAttributes()),'updated_at'),
                'after' => array_except($this->getChanges(),'updated_at')
            ];
        }

    }
}
