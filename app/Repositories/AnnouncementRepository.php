<?php

namespace App\Repositories;

use App\Announcement;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Cache\Repository as Cache;
use App\Repositories\AnnouncementRepositoryInterface;

class AnnouncementRepository extends BaseRepository implements AnnouncementRepositoryInterface
{
    /**
     * @var Cache
     */
    private $cache;

    /**
     * AnnouncementRepository contructor.
     *
     * @param Announcement $model
     */
    public function __construct(Announcement $model, Cache $cache)
    {
        parent::__construct($model);

        $this->cache = $cache;
    }

    public function allWithRelations(array $relations): Collection
    {
        return $this->model->with($relations)->get();
    }

    public function featured(int $limit): Collection
    {
        return $this->cache->remember(
            'announcements.featured',
            now()->addMinutes(10),
            function () use ($limit) {
                return $this->model->featured()->limit($limit)->get();
            }
        );
    }
}
