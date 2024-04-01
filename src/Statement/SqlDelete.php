<?php declare(strict_types=1);

namespace OpenCore\Orm\Statement;

use OpenCore\Orm\Ast\SqlDeleteStatement;
use OpenCore\Orm\SqlTable;
use OpenCore\Orm\SqlField;
use OpenCore\Orm\Utils\SqlUtils;
use OpenCore\Orm\Sql;

final class SqlDelete extends SqlBuilder {

  private readonly SqlDeleteStatement $st;

  public function __construct(SqlTable $table) {
    $this->st = new SqlDeleteStatement($table);
  }

  public function whereEquals(SqlField $field, mixed $value): self {
    $this->st->whereCondition = SqlUtils::andEqualsCondition($this->st->whereCondition, $field, $value);
    return $this;
  }

  public function build(): Sql {
    $ret = new Sql();
    $this->st->buildInto($ret);
    return $ret;
  }

}
