<?php

namespace EightyNine\Approvals\Traits;


use EightyNine\Approvals\Forms\Actions\ApproveAction;
use EightyNine\Approvals\Forms\Actions\DiscardAction;
use EightyNine\Approvals\Forms\Actions\RejectAction;
use EightyNine\Approvals\Forms\Actions\SubmitAction;
use EightyNine\Approvals\Models\ApprovableModel;
use Exception;
use Filament\Actions\Action;

trait HasApprovalHeaderActions
{

    /**
     * @throws \Exception
     */
    protected function getHeaderActions(): array
    {
        return [
            ...$this->getApprovalHeaderActions()
        ];
    }

    /**
     * @throws \Exception
     */
    protected function getApprovalHeaderActions(): array
    {
        return [
            ApproveAction::make(),
            RejectAction::make(),
            DiscardAction::make(),
            SubmitAction::make(),
            $this->getOnCompletionAction()
                ->visible(fn (ApprovableModel $record) => $record->isApprovalCompleted())
        ];
    }

    /**
     * Get the completion action
     *
     * @return Filament\Actions\Action
     * @throws Exception
     */
    protected function getOnCompletionAction(): Action
    {
        throw new \RuntimeException("Completion action not defined");
    }
}
