<?php

namespace EightyNine\Approvals\Tables\Actions;

use Closure;
use EightyNine\Approvals\Models\ApprovableModel;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DiscardAction extends Action
{

    public static function getDefaultName(): ?string
    {
        return __('Discard');
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->color('danger')
            ->action(__('Discard'))
            ->visible(
                fn (Model $record) =>
                $record->canBeApprovedBy(Auth::user()) &&
                    $record->isRejected()
            )
            ->requiresConfirmation();
    }


    public function action(Closure | string | null $action): static
    {
        if ($action !== __('Discard')) {
            throw new \RuntimeException(__('You\'re unable to override the action for this plugin'));
        }

        $this->action = $this->discardModel();

        return $this;
    }


    /**
     * Discard data function.
     *
     */
    private function discardModel(): Closure
    {
        return function (array $data, ApprovableModel $record): bool {
            $record->discard(null, Auth::user());
            Notification::make()
                ->title(__('Discarded successfully'))
                ->success()
                ->send();

            return true;
        };
    }
}
