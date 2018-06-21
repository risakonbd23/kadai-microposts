<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;


    protected $fillable = [
        'name', 'email', 'password',
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    public function follow($userId)
    {
    
        $exist = $this->is_following($userId);

        $its_me = $this->id == $userId;

        if ($exist || $its_me) {

        return false;
        } else {
        
        $this->followings()->attach($userId);
        return true;
        }
    }  

    public function unfollow($userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {

        $this->followings()->detach($userId);
        return true;
        } else {

        return false;
        }
    }

    public function is_following($userId) 
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    //以下favorite
    public function favorite()
    {
        return $this->belongsToMany(Micropost::class, 'favorite', 'user_id', 'favorite_id')->withTimestamps();
    }
    
    public function fav($micropostId)
    {    
        $exist = $this->is_favoriting($micropostId);
    
        if ($exist) {
        return false;
        } else {
        
        $this->favorite()->attach($micropostId);
        return true;
        }
    }
    
    public function unfav($micropostId)
    {
        $exist = $this->is_favoriting($micropostId);
        
        if ($exist) {
        $this->favorite()->detach($micropostId);
        return true;
        
        } else {
        return false;
    }
    }
            
    public function is_favoriting($micropostId) 
    {
       return $this->favorite()->where('favorite_id', $micropostId)->exists();
    }         
            
}