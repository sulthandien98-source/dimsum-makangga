<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'customer_name',
        'phone',
        'address',
        'total_price',
        'status',
        'payment_proof',
        'payment_status',
        'verified_at',
        'verified_by',
        'rejection_reason',
        'rejected_at',
        'rejected_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | ORDER STATUS CONSTANTS
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING   = 'pending';
    public const STATUS_WAITING   = 'menunggu_konfirmasi';
    public const STATUS_DIPROSES  = 'diproses';
    public const STATUS_SELESAI   = 'selesai';
    public const STATUS_REJECTED  = 'rejected';

    public const STATUSES = [
        self::STATUS_PENDING => [
            'label' => 'Pending',
            'color' => 'yellow',
        ],
        self::STATUS_WAITING => [
            'label' => 'Menunggu Konfirmasi',
            'color' => 'orange',
        ],
        self::STATUS_DIPROSES => [
            'label' => 'Diproses',
            'color' => 'blue',
        ],
        self::STATUS_SELESAI => [
            'label' => 'Selesai',
            'color' => 'green',
        ],
        self::STATUS_REJECTED => [
            'label' => 'Ditolak',
            'color' => 'red',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | PAYMENT STATUS CONSTANTS
    |--------------------------------------------------------------------------
    */

    public const PAYMENT_PENDING  = 'pending';
    public const PAYMENT_PAID     = 'paid';
    public const PAYMENT_REJECTED = 'rejected';

    public const PAYMENT_STATUSES = [
        self::PAYMENT_PENDING => [
            'label' => 'Menunggu Verifikasi',
            'color' => 'yellow',
        ],
        self::PAYMENT_PAID => [
            'label' => 'Pembayaran Diterima',
            'color' => 'green',
        ],
        self::PAYMENT_REJECTED => [
            'label' => 'Pembayaran Ditolak',
            'color' => 'red',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status]['label'] ?? 'Unknown';
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUSES[$this->status]['color'] ?? 'gray';
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status]['label'] ?? 'Menunggu Verifikasi';
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status]['color'] ?? 'yellow';
    }

    public function getPaymentProofUrlAttribute(): ?string
    {
        if (!$this->payment_proof) {
            return null;
        }
        return asset($this->payment_proof);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function hasPaymentProof(): bool
    {
        return !empty($this->payment_proof);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function isPaymentRejected(): bool
    {
        return $this->payment_status === self::PAYMENT_REJECTED;
    }

    public function isPaymentPending(): bool
    {
        return $this->payment_status === self::PAYMENT_PENDING;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isWaiting(): bool
    {
        return $this->status === self::STATUS_WAITING;
    }

    public function isProcessed(): bool
    {
        return $this->status === self::STATUS_DIPROSES;
    }

    public function isDone(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function isOrderRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
