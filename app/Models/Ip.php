<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateTimeZone;
class Ip extends Model
{
    use HasFactory;
    protected $primaryKey = 'Ip_address';
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'Ip_address',
        'nb_tentive',
        'next_possible_connexion',
        'last_request_in_5_min',
        'first_request_in_5_min'
    ];

    public static function haveAccess($ip)
    {
      

        $user_ip=Ip::find($ip);
        $date=new DateTime();
        $date->setTimezone(new DateTimeZone('Europe/Paris'));

        if($user_ip){
            $next_possible_connexion=new DateTime($user_ip->next_possible_connexion);
            $next_possible_connexion->setTimezone(new DateTimeZone('Europe/Paris'));
            $next_possible_connexion->modify("-2 hour");            
            $interval =strtotime($user_ip->last_request_in_5_min)-strtotime($user_ip->first_request_in_5_min);
        if (($user_ip->next_possible_connexion && $next_possible_connexion <= $date) || $interval >300) {               
                $user_ip->next_possible_connexion=null;
                $user_ip->nb_tentive=0;
                $user_ip->first_request_in_5_min=$date;
                $user_ip->last_request_in_5_min=$date;
                $user_ip->save();
            }else if ($user_ip->next_possible_connexion){
                $user_ip=0;
            }else{
                $user_ip->last_request_in_5_min=$date;
                $user_ip->save();
            }
        }
        return $user_ip;
    }

}
