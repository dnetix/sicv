<?php

namespace App\Models\Articles\Actions;

use App\Models\Core\Commander\Command;

class CreateOrRetrieveArticleCommand extends Command
{
    public $description;
    public $weight;
    public $article_type_id;
    /**
     * Its only possible because if only a character has been altered it doesn't refeer to the same article.
     * @var
     */
    public $possible_id;

    private $fields = [
        'description',
        'weight',
        'article_type_id',
        'possible_id',
    ];

    /**
     * @param $input
     */
    public function __construct($input)
    {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                if (in_array($key, $this->fields) && !empty($value)) {
                    $this->$key = $value;
                }
            }
        }
    }
}
