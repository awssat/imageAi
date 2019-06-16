# Php ImageaAi

Php soultion for https://github.com/OlafenwaMoses/ImageAI . try AI features in Php with help of python libraries

## Requirements 
 First you need to install https://github.com/OlafenwaMoses/ImageAI with all requirements 
## Install

Via Composer
```bash
composer require awssat/imageai
```


## Usage


```php
$imageAi = imageAi::image($img)->model('RetinaNet', '/path/to/resnet50_coco_best_v2.0.1.h5')->detect();
```
##### Result

```php

$imageAi->results = 
        [
            [
                "name": "car"
                "percentage": 97.267699241638
                "box_points": [
                                1392
                                116
                                3541
                                1276
                            ]
                "image": Intervention\Image\Image //object iamge
            ]
      ]
```

you should always define a model that supported in OlafenwaMoses/ImageAI 

### Model types

```
RetinaNet
YOLOv3
TinyYOLOv3
```

you must download the RetinaNet, YOLOv3 or TinyYOLOv3 object detection model via the links below: <br> <br>
 <span><b>- <a href="https://github.com/OlafenwaMoses/ImageAI/releases/download/1.0/resnet50_coco_best_v2.0.1.h5" style="text-decoration: none;" >RetinaNet</a></b> <b>(Size = 145 mb, high performance and accuracy, with longer detection time) </b></span> <br>

<span><b>- <a href="https://github.com/OlafenwaMoses/ImageAI/releases/download/1.0/yolo.h5" style="text-decoration: none;" >YOLOv3</a></b> <b>(Size = 237 mb, moderate performance and accuracy, with a moderate detection time) </b></span> <br>

<span><b>- <a href="https://github.com/OlafenwaMoses/ImageAI/releases/download/1.0/yolo-tiny.h5" style="text-decoration: none;" >TinyYOLOv3</a></b> <b>(Size = 34 mb, optimized for speed and moderate performance, with fast detection time) </b></span> <br><br>

### Other use cases

#### Speed
You can define speed of detection (affect accuracy) by simply calling

```php
$imageAi = imageAi::image($img)->speed('fast')->model('RetinaNet', '/path/to/resnet50_coco_best_v2.0.1.h5')->detect();
```

supported speeds (fast, faster, fastest, flash)

#### Specfic objects

You can only detect custom objects

```php
$imageAi = imageAi::image($img)->customObjects(['car'])->model('RetinaNet', '/path/to/resnet50_coco_best_v2.0.1.h5')->detect();
```

#### Percentage

Define a minimum percentage of detection proccess

```php
$imageAi = imageAi::image($img)->customObjects(['car'])->percentage(90)->model('RetinaNet', '/path/to/resnet50_coco_best_v2.0.1.h5')->detect();
```


## Contributing

You are very welcome to contribute and improve this package.


## Credits

- [Bader][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-author]: https://github.com/if4lcon
[link-contributors]: ../../contributors
