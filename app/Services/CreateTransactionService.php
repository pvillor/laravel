<?php

namespace App\Services;

use App\Exceptions\AppError;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CreateTransactionService
{
  public function execute(array $data)
  {
    $userPayer = $this->findUser($data['payer']);

    if ($userPayer->type == 'SELLER') {
      throw new AppError('Tipo de usuário inválido', 403);
    }

    if ($userPayer->balance < $data['value']) {
      throw new AppError('Saldo insuficiente', 400);
    }

    $userPayee = $this->findUser($data['payee']);

    $userPayer->balance -= $data['value'];
    $userPayee->balance += $data['value'];

    $userPayer->save();
    $userPayee->save();

    return Transaction::create([
      'value'    => $data['value'],
      'payer_id' => $userPayer->id,
      'payee_id' => $userPayee->id
    ]);
  }

  private function findUser($id)
  {
    $user = User::find($id);

    if (is_null($user)) {
      throw new AppError("Usuário $id não encontrado", 404);
    }

    return $user;
  }
}
