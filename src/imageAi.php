<?php

namespace Awssat\ImageAi;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Exception\ProcessFailedException;

class imageAi
{
    public $image;
    public $results;
    protected $pythonGenerator;
    protected $fsObject;
    protected $imageManager;
    protected $execPath;

    /**
     * imageAi constructor.
     * @param $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
        $this->fsObject = new Filesystem();
        $this->imageManager = new ImageManager();
        $this->pythonGenerator = new PythonScript($image);
        $this->execPath = $this->pythonGenerator->currentDirPath . "/exec.py";
    }

    public function percentage(int $percentage)
    {
        $this->pythonGenerator->percentage = $percentage;

        return $this;
    }

    public function customObjects(array $objects)
    {
        $this->pythonGenerator->customObjects = $objects;

        return $this;
    }

    public function speed($speed)
    {
        if(!in_array($speed, ['normal', 'fast', 'faster', 'fastest', 'flash'])) {
            throw  new \Exception('speed provided not supported');
        }

        $this->pythonGenerator->speed = $speed;

        return $this;
    }

    public function model($type, $path)
    {
        if(!in_array($type, ['RetinaNet', 'TinyYOLOv3', 'YOLOv3'])) {
            throw  new \Exception('Type provided not supported');
        }

        $this->pythonGenerator->modelType = $type;
        $this->pythonGenerator->modelPath = $path;

        return $this;
    }

    static public function image(Image $image)
    {
        return (new self($image));
    }

    public function detect()
    {
        //save a copy on working dir
        $this->image->save($this->pythonGenerator->imagePath);

        $this->createExecPythonFile();

        $this->results = array_map(function ($object)  {
                $object['image'] = $this->imageManager->make($object['path']);
                unset($object['path']);
                return (object) $object;
        }, $this->execAndGetOutput());

        $this->truncateCaches();

        return $this;
    }

    protected function getResults($output)
    {
        return json_decode($this->fixJSON($output), true) ?? [];
    }

    protected function fixJSON($JSON)
    {
        $JSON = str_replace("'","\"", $JSON);
        $JSON = str_replace("array(","", $JSON);
        $JSON = str_replace("])","]", $JSON);
        $JSON = str_replace(")","]", $JSON);
        $JSON = str_replace("(","[", $JSON);

        return $JSON;
    }

    protected function createExecPythonFile()
    {
        try {
            $this->fsObject->remove($this->execPath);

            if (!$this->fsObject->exists($this->execPath)) {
                $this->fsObject->touch($this->execPath);
                $this->fsObject->chmod($this->execPath, 0777);
                $this->fsObject->dumpFile($this->execPath, $this->pythonGenerator->generate());
            }
        } catch (\Exception $exception) {
            throw  new \Exception("Error creating file at" . $exception->getPath());
        }
    }

    protected function truncateCaches()
    {
        $this->fsObject->remove($this->execPath);
        $this->fsObject->remove($this->pythonGenerator->imagePath);
        $this->fsObject->remove($this->pythonGenerator->imageDetectedPath);
        $this->fsObject->remove($this->pythonGenerator->imageDetectedPath . '-objects');
    }

    protected function execAndGetOutput()
    {
        $process = new Process(['python3', $this->execPath]);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {

            $this->truncateCaches();

            throw new ProcessFailedException($process);
        }

        return  $this->getResults($process->getOutput());
    }
}