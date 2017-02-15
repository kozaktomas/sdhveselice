<?php

namespace App\Presenters;


use App\Model\FileManager;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;

class FilePresenter extends BasePresenter
{

    /** @var FileManager @inject */
    public $fileManager;

    protected function startup()
    {
        parent::startup();
        if (!$this->getUser()->isLoggedIn()) {
            throw new BadRequestException;
        }
    }

    public function renderDefault()
    {
        $this->template->files = $this->fileManager->getAllFiles();
    }

    public function createComponentUploadForm()
    {
        $form = new Form();
        $form->addUpload('file', 'Soubor:');
        $form->addSubmit('ok', 'Odeslat');
        $form->onSuccess[] = [$this, 'uploadFormSubmitted'];
        return $form;
    }

    public function uploadFormSubmitted(Form $form)
    {
        $values = $form->getValues();
        /** @var FileUpload $fileUpload */
        $fileUpload = $values->file;
        $this->fileManager->uploadFile($fileUpload);

        $this->redirect('default');
    }

    public function actionDelete($file)
    {
        $this->fileManager->deleteFile($file);
        $this->redirect('default');
    }

} 