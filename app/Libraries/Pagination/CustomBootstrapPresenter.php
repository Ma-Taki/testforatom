<?php

namespace App\Libraries\Pagination;

use Illuminate\Support\HtmlString;
use Illuminate\Pagination\BootstrapThreePresenter;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Contracts\Pagination\Presenter as PresenterContract;

class CustomBootstrapPresenter extends BootstrapThreePresenter
{
    use CustomBootstrapThreeNextPreviousButtonRendererTrait, CustomUrlWindowPresenterTrait;

    /**
     * Create a new Bootstrap presenter instance.
     *
     * @param  \Illuminate\Contracts\Pagination\Paginator  $paginator
     * @param  \Illuminate\Pagination\UrlWindow|null  $window
     * @return void
     */
    public function __construct(PaginatorContract $paginator, CustomUrlWindow $window = null)
    {
        $this->paginator = $paginator;
        $this->window = is_null($window) ? CustomUrlWindow::make($paginator) : $window->get();
    }

    /**
     * Convert the URL window into Bootstrap HTML.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function render()
    {
        if ($this->hasPages()) {
            return new HtmlString(sprintf(
                '<ul class="page">%s %s %s</ul>',
                $this->getPreviousButton(),
                $this->getLinks(),
                $this->getNextButton()
            ));
        }

        return '';
    }
}
