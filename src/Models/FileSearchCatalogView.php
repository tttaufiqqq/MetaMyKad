<?php

declare(strict_types=1);

namespace MetaMyKad\Models;

final class FileSearchCatalogView extends BaseModel
{
    protected string $table = 'vw_file_search_catalog';
    protected string $primaryKey = 'file_id';

    public function searchText(?string $keyword = null, ?string $tag = null): array
    {
        return $this->callProcedure('sp_search_text_files', [$keyword, $tag]);
    }

    public function searchContent(
        ?string $photoCategory = null,
        ?string $expression = null,
        ?string $audioTier = null,
        ?string $videoTier = null
    ): array {
        return $this->callProcedure('sp_search_content_files', [
            $photoCategory,
            $expression,
            $audioTier,
            $videoTier,
        ]);
    }
}
