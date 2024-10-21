<?php 

namespace News\Repositories\Preference;

use News\Abstracts\EloquentRepository;
use News\Models\Preference;

class PreferenceEloquentRepository extends EloquentRepository implements PreferenceRepositoryInterface
{
    public function __construct(Preference $preference)
    {
        $this->model = $preference;
    }

    public function getPreferencesByUserId($userId)
    {
        return $this->model::where('user_id', $userId)->get();
    }

    public function createOrUpdatePreferences($userId, array $data)
    {
        return $this->model::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }
}
