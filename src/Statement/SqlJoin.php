<?php declare(strict_types=1);

namespace OpenCore\Orm\Statement;

use OpenCore\Orm\SqlField;
use OpenCore\Orm\SqlTable;
use OpenCore\Orm\Ast\SqlJoinSpec;
use OpenCore\Orm\Utils\SqlUtils;

final class SqlJoin {

  public readonly SqlJoinSpec $st;

  public function __construct(int $type, SqlTable $table) {
    $this->st = new SqlJoinSpec($type, $table);
  }

  public function whereEquals(SqlField $field, mixed $value): self {
    $this->st->onCondition = SqlUtils::andEqualsCondition($this->st->onCondition, $field, $value);
    return $this;
  }

}
