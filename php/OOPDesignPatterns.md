# PHP OOP Concepts and Design Patterns

This document explains key concepts in PHP Object-Oriented Programming (OOP) and commonly used design patterns. Each section includes examples to illustrate the concepts and patterns in action.

---

## **Object-Oriented Programming (OOP) Concepts**

### **Four Pillars of OOP**

#### 1. **Encapsulation**
Encapsulation bundles data (variables) and methods (functions) into a single unit (class), restricting access to protect the internal state of the object.

```php
class User {
    private $name;

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
}

$user = new User();
$user->setName("Alice");
echo $user->getName(); // Output: Alice
```

#### 2. **Inheritance**
Inheritance allows a class to inherit properties and methods from another class, promoting code reuse.

```php
class Animal {
    public function speak() {
        echo "This is an animal.\n";
    }
}

class Dog extends Animal {
    public function speak() {
        echo "Bark!\n";
    }
}

$dog = new Dog();
$dog->speak(); // Output: Bark!
```

#### 3. **Polymorphism**
Polymorphism allows methods to behave differently based on the object that calls them.

```php
class Shape {
    public function draw() {
        echo "Drawing a shape.\n";
    }
}

class Circle extends Shape {
    public function draw() {
        echo "Drawing a circle.\n";
    }
}

$shapes = [new Shape(), new Circle()];
foreach ($shapes as $shape) {
    $shape->draw();
}
```

#### 4. **Abstraction**
Abstraction hides unnecessary details and shows only essential features using abstract classes and interfaces.

```php
interface Vehicle {
    public function start();
}

class Car implements Vehicle {
    public function start() {
        echo "Car started.\n";
    }
}

$car = new Car();
$car->start(); // Output: Car started.
```

---

## **Design Patterns**

### **1. Creational Design Patterns**

#### **1.1 Singleton**
Ensures a class has only one instance and provides a global point of access to it.

```php
class Singleton {
    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Singleton();
        }
        return self::$instance;
    }
}

$singleton1 = Singleton::getInstance();
$singleton2 = Singleton::getInstance();
var_dump($singleton1 === $singleton2); // Output: true
```

#### **1.2 Factory**
Creates objects without specifying the exact class to instantiate.

```php
class ShapeFactory {
    public static function create($type) {
        switch ($type) {
            case 'circle':
                return new Circle();
            case 'square':
                return new Square();
            default:
                throw new Exception("Invalid shape type.");
        }
    }
}

$circle = ShapeFactory::create('circle');
```

#### **1.3 Builder**
Constructs complex objects step-by-step.

```php
class Car {
    public $engine;
    public $wheels;
}

class CarBuilder {
    private $car;

    public function __construct() {
        $this->car = new Car();
    }

    public function addEngine($engine) {
        $this->car->engine = $engine;
        return $this;
    }

    public function addWheels($wheels) {
        $this->car->wheels = $wheels;
        return $this;
    }

    public function build() {
        return $this->car;
    }
}

$car = (new CarBuilder())
    ->addEngine("V8")
    ->addWheels(4)
    ->build();
```

---

### **2. Structural Design Patterns**

#### **2.1 Adapter**
Allows incompatible interfaces to work together.

```php
interface MediaPlayer {
    public function play($filename);
}

class MP3Player implements MediaPlayer {
    public function play($filename) {
        echo "Playing MP3: $filename";
    }
}

class MediaAdapter implements MediaPlayer {
    private $mp3Player;

    public function __construct(MP3Player $mp3Player) {
        $this->mp3Player = $mp3Player;
    }

    public function play($filename) {
        $this->mp3Player->play($filename);
    }
}

$player = new MediaAdapter(new MP3Player());
$player->play("song.mp3");
```

#### **2.2 Decorator**
Adds new functionality to objects dynamically.

```php
interface Coffee {
    public function getCost();
    public function getDescription();
}

class SimpleCoffee implements Coffee {
    public function getCost() {
        return 5;
    }

    public function getDescription() {
        return "Simple Coffee";
    }
}

class MilkDecorator implements Coffee {
    private $coffee;

    public function __construct(Coffee $coffee) {
        $this->coffee = $coffee;
    }

    public function getCost() {
        return $this->coffee->getCost() + 2;
    }

    public function getDescription() {
        return $this->coffee->getDescription() . ", with Milk";
    }
}

$coffee = new MilkDecorator(new SimpleCoffee());
echo $coffee->getCost(); // Output: 7
echo $coffee->getDescription(); // Output: Simple Coffee, with Milk
```

---

### **3. Behavioral Design Patterns**

#### **3.1 Observer**
Notifies multiple objects when the state of another object changes.

```php
class Subject {
    private $observers = [];

    public function attach($observer) {
        $this->observers[] = $observer;
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update();
        }
    }
}

class Observer {
    public function update() {
        echo "Observer notified.\n";
    }
}

$subject = new Subject();
$observer = new Observer();
$subject->attach($observer);
$subject->notify();
```

#### **3.2 Strategy**
Defines a family of algorithms and makes them interchangeable at runtime.

```php
interface PaymentStrategy {
    public function pay($amount);
}

class PayPal implements PaymentStrategy {
    public function pay($amount) {
        echo "Paid $amount using PayPal.";
    }
}

class CreditCard implements PaymentStrategy {
    public function pay($amount) {
        echo "Paid $amount using Credit Card.";
    }
}

class PaymentContext {
    private $strategy;

    public function setStrategy(PaymentStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function pay($amount) {
        $this->strategy->pay($amount);
    }
}

$context = new PaymentContext();
$context->setStrategy(new PayPal());
$context->pay(100);
```

---

### **Why Use Design Patterns?**

1. **Code Reusability**: Promotes DRY (Don't Repeat Yourself).
2. **Scalability**: Makes your code easier to extend.
3. **Maintainability**: Provides a clear structure for large projects.
4. **Flexibility**: Improves how components interact.

---
