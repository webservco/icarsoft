# webservco/icarsoft

Utilities for processing iCarsoft car diagnostic tool log files data.

---

## Examples

### Data

```php
$processor = new \WebServCo\ICarsoft\Processors\Data\Processor($filePath);
try {
    $processor->run();
    print_r($processor->getHeader());
    print_r($processor->getTitle());
    print_r($processor->getInfo());
    print_r($processor->getFrames());
} catch (\WebServCo\ICarsoft\Exceptions\ProcessorException $e) {
    echo $e->getMessage();
} catch (\WebServCo\ICarsoft\Exceptions\ICarsoftException $e) {
    echo $e->getMessage();
}
```

### Module Information

```php
$processor = new \WebServCo\ICarsoft\Processors\Info\Processor($filePath);
try {
    $processor->run();
    print_r($processor->getHeader());
    print_r($processor->getTitle());
    print_r($processor->getInfo());
    print_r($processor->getContent());
} catch (\WebServCo\ICarsoft\Exceptions\ProcessorException $e) {
    echo $e->getMessage();
} catch (\WebServCo\ICarsoft\Exceptions\ICarsoftException $e) {
    echo $e->getMessage();
}
```

---

## Notes

Some log keys have duplicate names.
