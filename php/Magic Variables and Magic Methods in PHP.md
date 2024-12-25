### Magic Variables and Magic Methods in PHP

PHP provides **magic variables** and **magic methods** that perform special behaviors. These are typically used for tasks like debugging, object handling, and dynamic functionality. Let’s dive into both:

---

### **Magic Variables**

Magic variables in PHP are predefined and provide information about the current state of the script or environment. They all start with `__` (double underscores).

1. **`__LINE__`**
   - **Description**: Returns the current line number in the script.
   - **Example**:
     ```php
     echo "This is line " . __LINE__;
     ```

2. **`__FILE__`**
   - **Description**: Returns the full path and filename of the file being executed.
   - **Example**:
     ```php
     echo "This script is located at " . __FILE__;
     ```

3. **`__DIR__`**
   - **Description**: Returns the directory of the current file.
   - **Example**:
     ```php
     echo "This script is in the directory: " . __DIR__;
     ```

4. **`__FUNCTION__`**
   - **Description**: Returns the name of the current function.
   - **Example**:
     ```php
     function test() {
         echo "Function name is: " . __FUNCTION__;
     }
     test();
     ```

5. **`__CLASS__`**
   - **Description**: Returns the name of the current class.
   - **Example**:
     ```php
     class Example {
         public function showClassName() {
             echo "Class name is: " . __CLASS__;
         }
     }
     $obj = new Example();
     $obj->showClassName();
     ```

6. **`__TRAIT__`**
   - **Description**: Returns the name of the trait being used.
   - **Example**:
     ```php
     trait MyTrait {
         public function showTraitName() {
             echo "Trait name is: " . __TRAIT__;
         }
     }
     class Example {
         use MyTrait;
     }
     $obj = new Example();
     $obj->showTraitName();
     ```

7. **`__METHOD__`**
   - **Description**: Returns the name of the current method.
   - **Example**:
     ```php
     class Example {
         public function showMethodName() {
             echo "Method name is: " . __METHOD__;
         }
     }
     $obj = new Example();
     $obj->showMethodName();
     ```

8. **`__NAMESPACE__`**
   - **Description**: Returns the name of the current namespace.
   - **Example**:
     ```php
     namespace MyNamespace;
     echo "Namespace is: " . __NAMESPACE__;
     ```

---

### **Magic Methods**

Magic methods are predefined methods in PHP that are triggered automatically when specific actions occur. They are prefixed with `__`.

#### **Object Lifecycle**
1. **`__construct()`**
   - **Description**: Called when an object is instantiated.
   - **Example**:
     ```php
     class Example {
         public function __construct() {
             echo "Object created!";
         }
     }
     $obj = new Example();
     ```

2. **`__destruct()`**
   - **Description**: Called when an object is destroyed or script ends.
   - **Example**:
     ```php
     class Example {
         public function __destruct() {
             echo "Object destroyed!";
         }
     }
     $obj = new Example();
     unset($obj);
     ```

#### **Overloading**
3. **`__get($name)`**
   - **Description**: Called when accessing an undefined or inaccessible property.
   - **Example**:
     ```php
     class Example {
         private $data = [];
         public function __get($name) {
             return $this->data[$name] ?? null;
         }
     }
     $obj = new Example();
     echo $obj->undefinedProperty; // Calls __get
     ```

4. **`__set($name, $value)`**
   - **Description**: Called when assigning a value to an undefined or inaccessible property.
   - **Example**:
     ```php
     class Example {
         private $data = [];
         public function __set($name, $value) {
             $this->data[$name] = $value;
         }
     }
     $obj = new Example();
     $obj->newProperty = "Value"; // Calls __set
     ```

5. **`__isset($name)`**
   - **Description**: Called when `isset()` or `empty()` is used on undefined or inaccessible properties.
   - **Example**:
     ```php
     class Example {
         private $data = ['name' => 'John'];
         public function __isset($name) {
             return isset($this->data[$name]);
         }
     }
     $obj = new Example();
     echo isset($obj->name); // Calls __isset
     ```

6. **`__unset($name)`**
   - **Description**: Called when `unset()` is used on an undefined or inaccessible property.
   - **Example**:
     ```php
     class Example {
         private $data = ['name' => 'John'];
         public function __unset($name) {
             unset($this->data[$name]);
         }
     }
     $obj = new Example();
     unset($obj->name); // Calls __unset
     ```

#### **Object Behavior**
7. **`__call($name, $arguments)`**
   - **Description**: Called when invoking an undefined or inaccessible method.
   - **Example**:
     ```php
     class Example {
         public function __call($name, $arguments) {
             echo "Method '$name' not found!";
         }
     }
     $obj = new Example();
     $obj->undefinedMethod(); // Calls __call
     ```

8. **`__callStatic($name, $arguments)`**
   - **Description**: Called for undefined or inaccessible static methods.
   - **Example**:
     ```php
     class Example {
         public static function __callStatic($name, $arguments) {
             echo "Static method '$name' not found!";
         }
     }
     Example::undefinedStaticMethod(); // Calls __callStatic
     ```

9. **`__toString()`**
   - **Description**: Called when the object is treated as a string.
   - **Example**:
     ```php
     class Example {
         public function __toString() {
             return "This is an object!";
         }
     }
     $obj = new Example();
     echo $obj; // Calls __toString
     ```

10. **`__invoke()`**
    - **Description**: Called when the object is used as a function.
    - **Example**:
      ```php
      class Example {
          public function __invoke() {
              return "Object invoked as a function!";
          }
      }
      $obj = new Example();
      echo $obj(); // Calls __invoke
      ```

#### **Serialization**
11. **`__sleep()`**
    - **Description**: Called before serialization to prepare the object.
    - **Example**:
      ```php
      class Example {
          private $data = "data";
          public function __sleep() {
              return ['data'];
          }
      }
      $obj = new Example();
      serialize($obj); // Calls __sleep
      ```

12. **`__wakeup()`**
    - **Description**: Called upon unserialization.
    - **Example**:
      ```php
      class Example {
          public function __wakeup() {
              echo "Object unserialized!";
          }
      }
      unserialize(serialize(new Example())); // Calls __wakeup
      ```

---

These magic methods and variables are essential tools for dynamic programming in PHP.
