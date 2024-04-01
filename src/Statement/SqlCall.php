<?php declare(strict_types=1);

namespace OpenCore\Orm\Statement;

use OpenCore\Orm\SqlField;
use OpenCore\Orm\Ast\SqlExprOpCall;
use OpenCore\Orm\Ast\SqlExprField;

final class SqlCall {

  public readonly SqlExprOpCall $st;

  public function __construct(string $fn) {
    $this->st = new SqlExprOpCall($fn);
  }

  public function withFieldArg(SqlField $field): self {
    $this->st->args[] = new SqlExprField($field);
    return $this;
  }

}
