<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

use MetaMyKad\Models\FileSearchCatalogView;
use MetaMyKad\Models\StudentProfileSummaryView;

final class SearchController extends BaseController
{
    public function attributeSearch(): void
    {
        $submitted     = isset($_GET['_search']);
        $gender        = trim($_GET['gender'] ?? '');
        $stateOfBirth  = trim($_GET['state_of_birth'] ?? '');
        $emailCategory = trim($_GET['email_category'] ?? '');
        $badge         = trim($_GET['badge'] ?? '');
        $fileType      = trim($_GET['file_type'] ?? '');

        $activeFilters = array_filter([
            'gender'         => $gender,
            'state_of_birth' => $stateOfBirth,
            'email_category' => $emailCategory,
            'badge'          => $badge,
            'file_type'      => $fileType,
        ], fn(string $v): bool => $v !== '');

        $results = [];
        if ($submitted) {
            $model   = new StudentProfileSummaryView();
            $results = $model->searchByAttribute(
                $gender ?: null,
                $stateOfBirth ?: null,
                $emailCategory ?: null,
                $badge ?: null,
                $fileType ?: null,
            );
        }

        $this->render('search-attribute', [
            'pageTitle'     => 'ABR Search',
            'results'       => $results,
            'activeFilters' => $activeFilters,
            'submitted'     => $submitted,
            'gender'        => $gender,
            'stateOfBirth'  => $stateOfBirth,
            'emailCategory' => $emailCategory,
            'badge'         => $badge,
            'fileType'      => $fileType,
        ]);
    }

    public function textSearch(): void
    {
        $submitted = isset($_GET['_search']);
        $keyword   = trim($_GET['keyword'] ?? '');
        $tag       = trim($_GET['tag'] ?? '');

        $activeFilters = array_filter([
            'keyword' => $keyword,
            'tag'     => $tag,
        ], fn(string $v): bool => $v !== '');

        $results = [];
        if ($submitted && ($keyword !== '' || $tag !== '')) {
            $model   = new FileSearchCatalogView();
            $results = $model->searchText($keyword ?: null, $tag ?: null);
        }

        $this->render('search-text', [
            'pageTitle'     => 'TBR Search',
            'results'       => $results,
            'activeFilters' => $activeFilters,
            'submitted'     => $submitted,
            'keyword'       => $keyword,
            'tag'           => $tag,
        ]);
    }

    public function contentSearch(): void
    {
        $submitted           = isset($_GET['_search']);
        $photoCategory       = trim($_GET['photo_category'] ?? '');
        $dominantExpression  = trim($_GET['dominant_expression'] ?? '');
        $audioDurationTier   = trim($_GET['audio_duration_tier'] ?? '');
        $videoResolutionTier = trim($_GET['video_resolution_tier'] ?? '');

        $activeFilters = array_filter([
            'photo_category'       => $photoCategory,
            'dominant_expression'  => $dominantExpression,
            'audio_duration_tier'  => $audioDurationTier,
            'video_resolution_tier' => $videoResolutionTier,
        ], fn(string $v): bool => $v !== '');

        $results = [];
        if ($submitted && $activeFilters !== []) {
            $model   = new FileSearchCatalogView();
            $results = $model->searchContent(
                $photoCategory ?: null,
                $dominantExpression ?: null,
                $audioDurationTier ?: null,
                $videoResolutionTier ?: null,
            );
        }

        $this->render('search-content', [
            'pageTitle'           => 'CBR Search',
            'results'             => $results,
            'activeFilters'       => $activeFilters,
            'submitted'           => $submitted,
            'photoCategory'       => $photoCategory,
            'dominantExpression'  => $dominantExpression,
            'audioDurationTier'   => $audioDurationTier,
            'videoResolutionTier' => $videoResolutionTier,
        ]);
    }
}
