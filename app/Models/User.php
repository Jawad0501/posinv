<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address_book' => 'object',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        // 'profile_photo_url',
        'full_name',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(fn (Model $model) => $model->customer_id = generate_invoice(1, true).rand(1111, 9999));
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        if ($this->last_name != null) {
            $name .= " $this->last_name";
        }

        return $name;
    }

    public function getAdvanceAmountAttribute()
    {
        if($this->advance_amount == null){
            return 0;
        }
        else {
            return $this->advance_amount;
        }
    }

    /**
     * get Normal users
     *
     * @param  mixed  $query
     */
    public function scopeUser($query)
    {
        return $query->where('role', 'User');
    }

    /**
     * get Riders
     *
     * @param  mixed  $query
     */
    public function scopeRider($query)
    {
        return $query->where('role', 'Rider');
    }

    /**
     * access permission
     *
     * @return void
     */
    public function hasPermission($permission): bool
    {
        return $this->role->permissions->where('slug', $permission)->first() ? true : false;
    }

    /**
     * one to many relationship define by role model
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the stock adjustment for the user.
     */
    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class, 'person_id');
    }

    /**
     * Get the expense for the user.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'person_id');
    }

    /**
     * Get the waste for the user.
     */
    public function wastes()
    {
        return $this->hasMany(Waste::class, 'person_id');
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the rider orders for the user.
     */
    public function riderOrders()
    {
        return $this->hasMany(Order::class, 'rider_id');
    }

    /**
     * Get the payments for the user.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the review item for the user.
     */
    public function reviews()
    {
        return $this->hasMany(OrderReview::class);
    }

    /**
     * Get all of the favorites for the User
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get all of the reservations for the User
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the giftCard associated with the User
     */
    public function giftCards()
    {
        return $this->hasMany(GiftCard::class);
    }

    /**
     * Get the giftCardTransitions associated with the User
     */
    public function giftCardTransitions()
    {
        return $this->hasMany(GiftCardTransaction::class);
    }
}
