<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Link extends Model
{
    /** @use HasFactory<\Database\Factories\LinkFactory> */
    use HasFactory;

    protected $fillable = ['link', 'name', 'sort'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function moveUp()
    {
        $this->move(-1);
    }

    public function moveDown()
    {
        $this->move(+1);
    }

    private function move(int $to)
    {
        DB::transaction(function () use ($to): void {
            $user = $this->user()->lockForUpdate()->firstOrFail();
            $this->refresh();
            $swapWith = $user->links()
                ->where('sort', $to < 0 ? '<' : '>', $this->sort)
                ->orderBy('sort', $to < 0 ? 'desc' : 'asc')
                ->lockForUpdate()
                ->first();

            if ($swapWith === null) {
                return;
            }

            $order = $this->sort;
            $this->fill(['sort' => $swapWith->sort])->save();
            $swapWith->fill(['sort' => $order])->save();
        });
    }
}
