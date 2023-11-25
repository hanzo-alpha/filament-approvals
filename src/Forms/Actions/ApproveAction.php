<?php

namespace EightyNine\Approvals\Forms\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ApproveAction extends Action
{

    public static function getDefaultName(): ?string
    {
        return __('Approve');
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->color('primary')
            ->action(__('Approve'))
            ->visible(
                fn (Model $record) =>
                $record->canBeApprovedBy(Auth::user()) &&
                    $record->isSubmitted() &&
                    !$record->isApprovalCompleted() &&
                    !$record->isDiscarded()
            )
            ->requiresConfirmation();
    }


    public function action(Closure | string | null $action): static
    {
        if ($action !== 'Approve') {
            throw new \Exception('You\'re unable to override the action for this plugin');
        }

        $this->action = $this->approveModel();

        return $this;
    }


    /**
     * Approve data function.
     *
     */
    private function approveModel(): Closure
    {
        return function (array $data, Model $record): bool {
            $record->approve(comment: null, user: Auth::user());
            Notification::make()
                ->title(__('Approved successfully'))
                ->success()
                ->send();
            return true;
        };
    }
}
