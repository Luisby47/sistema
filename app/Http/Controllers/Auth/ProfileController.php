<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\MoonShine\Pages\ProfilePage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use MoonShine\Fields\Fields;
use MoonShine\Fields\Image;
use MoonShine\Http\Controllers\MoonShineController;

use MoonShine\Pages\Page;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProfileController extends MoonShineController
{
    /**
     * @throws Throwable
     */
    public function store(ProfileFormRequest $request): Response
    {
        /** @var Page $page */
        $page = new (config('moonshine.pages.profile', ProfilePage::class))();
        $fields = Fields::make($page->fields());

        /** @var Image $image */
        $image = $fields
            ->onlyFields()
            ->findByClass(Image::class);


        $data = $request->validated();

        $resultData = [
            config('moonshine.auth.fields.username', 'email') => e($data['username']),
            config('moonshine.auth.fields.name', 'name') => e($data['name']),
        ];

        if (isset($data['password']) && filled($data['password'])) {
            $resultData[config('moonshine.auth.fields.password', 'password')] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if (! is_null($image)) {
            $this->applyImage($image, $resultData);
        }

        $resultData = array_filter(
            $resultData,
            static fn ($key): bool => $key !== 0 && $key !== '',
            ARRAY_FILTER_USE_KEY
        );

        $request->user()->update($resultData);

        if ($request->ajax()) {
            return $this->json(message: __('moonshine::ui.saved'));
        }

        $this->toast(
            __('moonshine::ui.saved'),
            'success'
        );

        return back();
    }

    /**
     * @throws Throwable
     */
    private function applyImage(Image $image, array &$result): void
    {
        $avatarColumn = config(
            'moonshine.auth.fields.avatar',
            'avatar'
        );

        $avatar = request()->file('avatar');
        $oldAvatar = request()->input('hidden_avatar', '');
        $currentAvatar = data_get(request()->user(), $avatarColumn, '');

        if (! is_null($avatar)) {
            $result[$avatarColumn] = $image->store($avatar);
            $image->deleteFile($oldAvatar);

            return;
        }

        $result[$avatarColumn] = $oldAvatar;

        if ($oldAvatar !== $currentAvatar) {
            $image->deleteFile($currentAvatar);
        }
    }
}
