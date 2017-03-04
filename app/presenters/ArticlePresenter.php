<?php

namespace Sdh\Veselice\Presenters;

use App\Control\VisualPaginator;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\DateTime;
use Nette\Http\FileUpload;
use Nette\Utils\Strings;

class ArticlePresenter extends BasePresenter
{

    /**
     * Vykresluje seznam článků včetně stránkování
     */
    public function renderList()
    {
        $itemPerPage = 15;

        $articles = $this->articleList->getArticles();


        $vp = new VisualPaginator($this, 'vp');
        $vp->setItemsPerPage($itemPerPage);
        $vp->setItemsCount(count($articles));

        $page = $vp->page;

        $articles = array_slice($articles, $vp->getPaginator()->getOffset(), $itemPerPage);
        $this->template->articles = $articles;
    }


    /**
     * Detail článku
     * @param $id
     */
    public function renderDetail($id)
    {
        $article = $this->articleTable->where('id', $id)->fetch();
        $this->template->article = $article;
        $this->template->cover = $this->galleryManager->getCover($article->image);

        $images = $this->galleryManager->getPhotos($id);
        $this->template->gallery = $images;
    }

    /**
     * @param int|null $id_article
     * @throws \Nette\Application\BadRequestException
     */
    public function renderAdmin($id_article = NULL)
    {
        if (!$this->getUser()->isLoggedIn()) {
            throw new BadRequestException;
        }
        $this->template->id_article = intval($id_article);
        $article = $this->articleTable->get($id_article);

        $images = $this->galleryManager->getPhotos($id_article);
        $this->template->gallery = $images;
        $this->template->article = $article;

        if ($article) {
            $this['articleForm']->setDefaults([
                'id' => $id_article,
                'title' => $article->title,
                'text' => $article->text,
                'imageIn' => $article->imageIn,
            ]);
            $this['photoForm']->setDefaults([
                'id' => $id_article,
            ]);
        }
    }

    /**
     * @return Form
     */
    public function createComponentArticleForm()
    {
        $form = new Form();
        $form->addText('title', 'Title:');
        $form->addCheckbox('imageIn', 'Obrázek v článku:');
        $form->addTextArea('text', 'Text:');
        $form->addUpload('image', 'Image:');
        $form->addHidden('id');
        $form->addSubmit('ok', 'Hotovo');
        $form->onSuccess[] = [$this, 'articleFormSubmitted'];
        return $form;
    }

    /**
     * @param Form $form
     * @throws BadRequestException
     */
    public function articleFormSubmitted(Form $form)
    {
        if (!$this->getUser()->isLoggedIn()) {
            throw new BadRequestException;
        }
        $values = $form->getValues();
        $id = (int)$values->id;
        unset($values->id);
        $data = [
            'title' => $values->title,
            'url' => Strings::webalize($values->title),
            'text' => $values->text,
            'created' => new DateTime(),
            'imageIn' => $values->imageIn,
        ];

        /** @var FileUpload $cover */
        $cover = $values->image;
        if ($cover->getName()) {
            $data['image'] = $this->galleryManager->uploadCover($cover);
        }

        if ($id) {
            $this->articleTable->where('id', $id)->update($data);
        } else {
            $this->articleTable->insert($data);
        }

        $this->redirect('list');
    }

    /**
     * Delete article
     * @param $id_article
     * @throws \Nette\Application\BadRequestException
     */
    public function actionDelete($id_article)
    {
        if (!$this->getUser()->isLoggedIn()) {
            throw new BadRequestException;
        }
        $this->articleTable->get($id_article)->delete();
        $this->redirect('list');
    }

    /**
     * Upload obrázku do galerie
     * @return Form
     */
    public function createComponentPhotoForm()
    {
        $form = new Form();
        $form->addUpload('photo', 'Obrázek: ', TRUE)
            ->setRequired(false)
            ->addRule(Form::IMAGE, 'Obrázek!');
        $form->addHidden('id');
        $form->addSubmit('ok', 'Nahraj');
        $form->onSuccess[] = [$this, 'photoFormSubmitted'];
        return $form;
    }

    public function photoFormSubmitted(Form $form)
    {
        if (!$this->getUser()->isLoggedIn()) {
            throw new BadRequestException;
        }
        $values = $form->getValues();
        $id = (int)$values->id;

        foreach ($values->photo as $photo) {
            /** @var FileUpload $photo */
            $this->galleryManager->uploadPhoto($id, $photo);
        }

        $this->redirect('admin', ['id_article' => $id]);
    }

    public function actionDeletePhoto($id_article, $name)
    {
        if (!$this->getUser()->isLoggedIn()) {
            throw new BadRequestException;
        }
        $this->galleryManager->deletePhoto($id_article, $name);
        $this->redirect('admin', ['id_article' => intval($id_article)]);
    }


}