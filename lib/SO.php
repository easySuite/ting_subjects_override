<?php

namespace SO;

use TingEntity;

/**
 * Class SubjectsOverrides.
 *
 * @package SO
 */
class SubjectsOverrides extends TingEntity {

  /**
   * SubjectsOverrides constructor.
   *
   * @param \TingEntity $entity
   *   TingEntity object.
   */
  public function __construct(TingEntity $entity) {
    parent::__construct();
    $this->entity = $entity;
  }

  /**
   * Get MARC-format subjects for ting_object.
   *
   * @return array|null
   *   Return array of MARC format subjects.
   */
  public function getSubjects() {
    $subjects = opensearch_get_object_marcxchange($this->entity->ding_entity_id);

    if (empty($subjects)) {
      return NULL;
    }

    $results = $subjects->getValue('667', '');

    $items = [];
    if (!empty($results)) {
      foreach ($results as $key => $item) {
        // Omitting results with numeric indexes.
        if (!is_numeric($key)) {
          // If there are several sub-fields (arrays), we will need to loop them
          // too.
          if (is_array($item)) {
            foreach ($item as $value) {
              $items[] = $value;
            }
          }
          else {
            $items[] = $item;
          }
        }
      }
    }

    return $this->entity->subjects = $items;
  }

}
