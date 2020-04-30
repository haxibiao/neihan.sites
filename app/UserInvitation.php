<?php

namespace App;

use App\Invitation;
use App\InvitationPhase;
use App\User;
use App\Wallet;
use Illuminate\Database\Eloquent\Model;

class UserInvitation extends Model
{
    use Traits\UserInvitationResolvers;
    use Traits\UserInvitationAttrs;
    use Traits\UserInvitationRepo;

    protected $fillable = [
        'user_id',
        'firends_total_contribute',
        'success_firends_count',
        'firends_count',
        'phase_id',
        'rate',
        'next_increment_at',
        'red_packet_phase_amount',
        'red_packet_invites_count',
    ];

    protected $casts = [
        'next_increment_at' => 'datetime',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function phase()
    {
        return $this->belongsTo(InvitationPhase::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'user_id', 'user_id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'user_id', 'user_id')->where('type', Wallet::UNION_WALLET);
    }

    public function rmbWallet()
    {
        return $this->belongsTo(Wallet::class, 'user_id', 'user_id')->where('type', Wallet::RMB_WALLET);
    }

    public function unionWallet()
    {
        return $this->belongsTo(Wallet::class, 'user_id', 'user_id')->where('type', Wallet::UNION_WALLET);
    }

    public function redPacket()
    {
        return $this->belongsTo(Wallet::class, 'user_id', 'user_id')->where('type', Wallet::RED_PACKET_WALLET);
    }
}
