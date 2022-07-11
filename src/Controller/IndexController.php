<?php

namespace App\Controller;

use App\Component\Events\EventsList;
use App\Entity\Event;
use App\Form\EventType;
use App\Service\Builder\Element;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;

class IndexController extends AbstractController
{
    /**
     *
     * @return JsonResponse
     */
    #[Route('/', name: 'index')]
    public function index(): JsonResponse
    {
        $root = new Element('div');

        $body = new Element('ui-body');
        $wrapper = new Element('ui-wrapper');
/*        $searchSection = new Element('ui-hero-banner');
        $select1 = new Element('ui-select');
        $searchSection->appendChild($select1);


        //$events->setProp('filters', json_encode(['MTB', 'Road']));
        $wrapper->appendChild($searchSection);*/
        $wrapper->appendChild((new EventsList())->create());
        $body->appendChild($wrapper);

        $root->appendChild($body);

        return new JsonResponse($root->toArray());
    }

    #[Route('/event/create', name: 'event.create')]
    public function eventCreate(): JsonResponse
    {
        $root = new Element('div');

        $body = new Element('ui-body');
        $wrapper = new Element('ui-wrapper');

        $steps = new Element('ui-step-wizard');
        $firstStep = new Element('ui-step', [
            'props' => [
                'title' => 'Деталі',
                'form' => [
                    'fields' => [
                        [
                            'name' => 'name',
                            'value' => '',
                            'validation' => [
                                'required' => true,
                                'message' => 'Будь ласка введіть назву події',
                            ]
                        ],
                        [
                            'name' => 'description',
                            'value' => '',
                            'validation' => [
                                'required' => true,
                                'message' => 'Будь ласка введіть опис події',
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $formItem1 = new Element('ui-form-item', [
            'props' => [
                'name' => 'name',
                'label' => 'Дайте назву події1:',
                'description' => 'Подивіться, як ваше ім’я відображається на сторінці події, а також список усіх місць, де використовуватиметься ваша назва події.  <a href="#" class="a-link">Взнати більше</a>'
            ]
        ], [
            new Element('ui-text-input')
        ]);
        $formItem2 = new Element('ui-form-item', [
            'props' => [
                'name' => 'description',
                'label' => 'Будь ласка, опишіть свою подію:',
                'description' => 'Напишіть кілька слів нижче, щоб описати свою подію та надайте будь-яку додаткову інформацію, таку як розклад, маршрут або будь-які спеціальні інструкції, необхідні для відвідування вашої події.  <a href="#" class="a-link">Взнати більше</a>'
            ]
        ], [
            new Element('ui-text-input')
        ]);
        $firstStep->appendChild($formItem1);
        $firstStep->appendChild($formItem2);

        $steps->appendChild($firstStep);
        $steps->appendChild(new Element('ui-step', [
            'props' => [
                'title' => 'Реєстрація',
                'form' => [
                    'fields' => [
                        [
                            'name' => 'name',
                            'value' => '',
                            'validation' => [
                                'required' => true,
                                'message' => 'Будь ласка введіть назву події',
                            ]
                        ],
                        [
                            'name' => 'description',
                            'value' => '',
                            'validation' => [
                                'required' => true,
                                'message' => 'Будь ласка введіть опис події',
                            ]
                        ]
                    ]
                ]
            ]
        ]));
        $steps->appendChild(new Element('ui-step', [
            'props' => [
                'title' => 'Налаштування',
            ]
        ]));


        $wrapper->appendChild(new Element('ui-breadcrumbs'));
        $wrapper->appendChild($steps);
        $body->appendChild($wrapper);

        $root->appendChild($body);

        return new JsonResponse($root->toArray());
    }

    #[Route('/events', name: 'events')]
    public function events(): JsonResponse
    {
        return new JsonResponse((new EventsList())->create()->toArray());
    }

    #[Route('/form', name: 'form')]
    public function form()
    {
        // creates a task object and initializes some data for this example
        $task = new Event();

        $form = $this->createForm(EventType::class, $task);

        $result = [];
        $items = $form->all();
        foreach ($items as $item) {
            $result[$item->getName()] = [
                'name' => $item->getName(),
                'type' => substr(strrchr(get_class($item->getConfig()->getType()->getInnerType()), '\\'), 1),
                'label' => $item->getConfig()->getOption('label'),
                'description' => $item->getConfig()->getOption('help'),
                'validation' => array_map(function ($value) {
                    return get_object_vars($value);
                }, $item->getConfig()->getOption('constraints')),
            ];
            /*dump($item->createView());
            dump($item->getConfig()->getType());
            dump($item->getConfig()->getOptions());*/
        }

        dd($result);

        dd($form->get('title')->getConfig()->getOptions());

        dd($form->get('title')->createView());

        dd('OLOLO');
    }
}