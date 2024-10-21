<?php 
namespace News\Repositories\Preference;
use News\Abstracts\RepositoryInterface;

interface PreferenceRepositoryInterface extends RepositoryInterface
{
    public function getPreferencesByUserId($userId);
    public function createOrUpdatePreferences($userId, array $data);
}
