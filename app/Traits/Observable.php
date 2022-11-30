<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

use App\Models\Log;
use App\Models\User;
use App\Models\Signup;
use App\Models\Mensa;
use App\Models\Faq;
use App\Models\ExtraOption;
use App\Models\MenuItem;
use App\Traits\Log\Severity;
use App\Traits\Log\Category;

use Illuminate\Support\Facades\Auth;

/**
 * Observable trait
 *
 * @package App\Traits
 */
trait Observable
{

    public static function bootObservable()
    {
        static::saved(function (Model $model) {
            // create or update?
            if( $model->wasRecentlyCreated ) {
                static::logChange( $model, 'CREATED' );
            } else {
                if( !$model->getChanges() ) {
                    return;
                }
                static::logChange( $model, 'UPDATED' );
            }
        });

        static::deleted(function (Model $model) {
            static::logChange( $model, 'DELETED' );
        });
    }

    /**
     * String to describe the model being updated / deleted / created
     *
     * Override this in your own model to customise - see below for example
     *
     * @return string
     */
    public static function logSubject(Model $model): string {
        return static::logImplodeAssoc($model->attributesToArray());
    }

    /**
     * Format an assoc array as a key/value string for logging
     * @return string
     */
    public static function logImplodeAssoc(array $attrs): string {
        return json_encode($attrs);
    }

    /**
     * String to describe the model being updated / deleted / created
     * @return string
     */
    public static function logChange( Model $model, string $action ) {
        if($action === 'CREATED'){$data =  $model->getAttributes(); } 
        elseif ($action === 'UPDATED' ) {$data =  $model->getChanges();}
        $text = (object) [
            'result' => "success",
            'action' => $action,
            'data' => $data,
        ];
        $log = new Log;
        $log->user_id = Auth::check() ? Auth::user()->id : User::firstWhere('name', 'SYSTEM')->id;
        $log->severity = Severity::Informational;
        $log->category = Category::Model;
        $log->text = json_encode($text);
        
        $model->Log()->save($log);

        
    }

}


