<?php declare(strict_types=1);

namespace OpenCore\Orm;

final class TableDefinition {

  private static array $defCache = [];
  private ?array $foreignToProp = null;
  private ?SqlTable $sqlTable = null;
  private ?array $allDefs = null;
  private ?array $sqlFieldsCache = null;
  private ?TableDefinition $parentDef = null;
  private ?array $allPropsCache = null;

  public function __construct(
    private readonly string $table,
    public readonly array $props,
    private readonly array $fields,
    private readonly ?array $propToForeign,
    public readonly ?string $primary,
    private readonly ?string $parentClass = null,
  ) {
  }

  public static function from(string $class): self {
    if (!isset (self::$defCache[$class])) {
      self::$defCache[$class] = $class::tableDefinition();
    }
    return self::$defCache[$class];
  }

  private function getParentDef(): TableDefinition {
    if ($this->parentDef === null) {
      $this->parentDef = TableDefinition::from($this->parentClass);
    }
    return $this->parentDef;
  }

  public function getSqlField(string $prop): SqlField {
    if (!isset ($this->sqlFieldsCache[$prop])) {
      if (!isset ($this->fields[$prop]) && $this->parentClass !== null) {
        $this->sqlFieldsCache[$prop] = $this->getParentDef()->getSqlField($prop);
      } else {
        $this->sqlFieldsCache[$prop] = new SqlField($this->getSqlTable(), $this->fields[$prop], $prop);
      }
    }
    return $this->sqlFieldsCache[$prop];
  }

  public function getAllDefs(): array {
    if ($this->allDefs === null) {
      $def = $this;
      while (true) {
        $this->allDefs[] = $def;
        if ($def->parentClass === null) {
          break;
        }
        $def = $def->getParentDef();
      }
    }
    return $this->allDefs;
  }

  public function getSqlTable(): SqlTable {
    if ($this->sqlTable === null) {
      $this->sqlTable = new SqlTable($this->table);
    }
    return $this->sqlTable;
  }

  public function getSqlPrimary(): SqlField {
    return $this->getSqlField($this->primary);
  }

  public function getAllProps(): array {
    if ($this->allPropsCache === null) {
      $ret = $this->props;
      if ($this->parentClass !== null) {
        foreach ($this->getParentDef()->getAllProps() as $prop) {
          if ($prop !== $this->primary) {
            $ret[] = $prop;
          }
        }
      }
      $this->allPropsCache = $ret;
    }
    return $this->allPropsCache;
  }

  public function getSqlFields(array $propNames): array {
    return array_map(fn(string $prop) => $this->getSqlField($prop), $propNames);
  }

  public function getForeignClass(string $prop): string {
    return $this->propToForeign[$prop];
  }

  public function getForeignProp(string $foreignClass): string {
    if ($this->foreignToProp === null) {
      $this->foreignToProp = array_flip($this->propToForeign);
    }
    return $this->foreignToProp[$foreignClass];
  }

  public static function builder(string $table, string $parentClass = null, ): TableDefinitionBuilder {
    return new TableDefinitionBuilder($table, $parentClass);
  }

}
