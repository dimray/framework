<?php

declare(strict_types=1);

namespace Framework;

class Controller
{
    protected array $errors = [];

    protected function addError($element, $value)
    {
        $this->errors[$element] = $value;
    }

    protected Request $request;

    protected Response $response;

    protected Viewer $viewer;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    public function setViewer(Viewer $viewer)
    {
        $this->viewer = $viewer;
    }

    protected function view(string $template, array $data = []): Response
    {
        $this->response->setBody($this->viewer->render($template, $data));

        return $this->response;
    }

    protected function redirect(string $url): Response
    {
        $this->response->redirect($url);

        return $this->response;
    }

    protected function emailView(string $template, array $data = []): string
    {
        return $this->viewer->render($template, $data);
    }
}
