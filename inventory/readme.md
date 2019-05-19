# Inventory Microservice Lumen

## Endpoints

API JSON Responses.
```
   # Create Category
   POST /api/v1/category
   formData : {
        'name' => 'some category name'
    }

   # Get all categories
   GET /api/v1/category
   
   # Get category by ID
   GET /api/v1/category/{id}

   # Create Product
   POST /api/v1/product
   formData : {
        'name' => 'some product name',
        'description' => 'some product description',
        'harga' => '1000000',
        'stock' => '5',
    }

   # Get all products
   GET /api/v1/product
   
   # Get product by ID
   GET /api/v1/product/{id}
```

**JSON Output** Sample
```
   # Create Category
   POST /api/v1/category
   {
       "success": "Category created"
   }
```
   
```
   # Get all categories
   GET /api/v1/category
   {
       "data": {
           "category": [
               {
                   "id": 1,
                   "name": "Automotive",
                   "created_at": {
                       "date": "2018-11-23 07:13:30.000000",
                       "timezone_type": 3,
                       "timezone": "UTC"
                   },
                   "product": [
                       {
                           "id": 1,
                           "name": "Incredible Linen Pants",
                           "description": "So she called softly after it, 'Mouse dear! Do come back again, and she could not even get her.",
                           "harga": 52486,
                           "stock": 14,
                           "created_at": {
                               "date": "2018-11-23 07:13:31.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       },
                       {
                           "id": 2,
                           "name": "Enormous Silk Chair",
                           "description": "Hatter: 'it's very rude.' The Hatter looked at the Cat's head began fading away the time. Alice.",
                           "harga": 64561,
                           "stock": 10,
                           "created_at": {
                               "date": "2018-11-23 07:13:31.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ]
               },
               {
                   "id": 2,
                   "name": "Garden",
                   "created_at": {
                       "date": "2018-11-23 07:13:30.000000",
                       "timezone_type": 3,
                       "timezone": "UTC"
                   },
                   "product": [
                       {
                           "id": 3,
                           "name": "Aerodynamic Aluminum Hat",
                           "description": "Stole the Tarts? The King and Queen of Hearts were seated on their slates, 'SHE doesn't believe.",
                           "harga": 91654,
                           "stock": 19,
                           "created_at": {
                               "date": "2018-11-23 07:13:36.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       },
                       {
                           "id": 4,
                           "name": "Mediocre Granite Keyboard",
                           "description": "Dormouse crossed the court, arm-in-arm with the time,' she said to the waving of the house, and.",
                           "harga": 15617,
                           "stock": 14,
                           "created_at": {
                               "date": "2018-11-23 07:13:36.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ]
               }
           ]
       },
       "links": [
           {
               "rel": "product",
               "href": "http://inventory.lm.local/api/v1/product",
               "method": "GET"
           },
           {
               "rel": "self",
               "href": "http://inventory.lm.local/api/v1/category",
               "method": "GET"
           }
       ]
   }
```
   
```   
   # Get category by ID
   GET /api/v1/category/{id}
    {
        "data": {
            "category": {
                "id": 1,
                "name": "Automotive",
                "created_at": {
                    "date": "2018-11-23 07:13:30.000000",
                    "timezone_type": 3,
                    "timezone": "UTC"
                },
                "product": [
                    {
                        "id": 1,
                        "name": "Incredible Linen Pants",
                        "description": "So she called softly after it, 'Mouse dear! Do come back again, and she could not even get her.",
                        "harga": 52486,
                        "stock": 14,
                        "created_at": {
                            "date": "2018-11-23 07:13:31.000000",
                            "timezone_type": 3,
                            "timezone": "UTC"
                        }
                    },
                    {
                        "id": 2,
                        "name": "Enormous Silk Chair",
                        "description": "Hatter: 'it's very rude.' The Hatter looked at the Cat's head began fading away the time. Alice.",
                        "harga": 64561,
                        "stock": 10,
                        "created_at": {
                            "date": "2018-11-23 07:13:31.000000",
                            "timezone_type": 3,
                            "timezone": "UTC"
                        }
                    }
                ]
            }
        }
    }
```

```
   # Create Product
   POST /api/v1/product
   {
       "success": "Product created"
   }
```
   
```
   # Get all products
   GET /api/v1/product
   {
       "data": {
           "product": [
               {
                   "id": 1,
                   "name": "Incredible Linen Pants",
                   "description": "So she called softly after it, 'Mouse dear! Do come back again, and she could not even get her.",
                   "harga": 52486,
                   "stock": 14,
                   "created_at": {
                       "date": "2018-11-23 07:13:31.000000",
                       "timezone_type": 3,
                       "timezone": "UTC"
                   },
                   "image": [
                       {
                           "id": 1,
                           "image": "/storage/0f8f1059b8e8ee2a5a46e76983fbf46f.jpg",
                           "created_at": {
                               "date": "2018-11-23 07:13:33.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ],
                   "category": [
                       {
                           "id": 1,
                           "name": "Automotive",
                           "created_at": {
                               "date": "2018-11-23 07:13:30.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ]
               },
               {
                   "id": 2,
                   "name": "Enormous Silk Chair",
                   "description": "Hatter: 'it's very rude.' The Hatter looked at the Cat's head began fading away the time. Alice.",
                   "harga": 64561,
                   "stock": 10,
                   "created_at": {
                       "date": "2018-11-23 07:13:31.000000",
                       "timezone_type": 3,
                       "timezone": "UTC"
                   },
                   "image": [
                       {
                           "id": 2,
                           "image": "/storage/8ec0489705dd6e0f2860d84b36047639.jpg",
                           "created_at": {
                               "date": "2018-11-23 07:13:36.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ],
                   "category": [
                       {
                           "id": 1,
                           "name": "Automotive",
                           "created_at": {
                               "date": "2018-11-23 07:13:30.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ]
               },
               {
                   "id": 3,
                   "name": "Aerodynamic Aluminum Hat",
                   "description": "Stole the Tarts? The King and Queen of Hearts were seated on their slates, 'SHE doesn't believe.",
                   "harga": 91654,
                   "stock": 19,
                   "created_at": {
                       "date": "2018-11-23 07:13:36.000000",
                       "timezone_type": 3,
                       "timezone": "UTC"
                   },
                   "image": [
                       {
                           "id": 3,
                           "image": "/storage/447b6eb193f5e8c2699281068f53a2e8.jpg",
                           "created_at": {
                               "date": "2018-11-23 07:13:39.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ],
                   "category": [
                       {
                           "id": 2,
                           "name": "Garden",
                           "created_at": {
                               "date": "2018-11-23 07:13:30.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ]
               },
               {
                   "id": 4,
                   "name": "Mediocre Granite Keyboard",
                   "description": "Dormouse crossed the court, arm-in-arm with the time,' she said to the waving of the house, and.",
                   "harga": 15617,
                   "stock": 14,
                   "created_at": {
                       "date": "2018-11-23 07:13:36.000000",
                       "timezone_type": 3,
                       "timezone": "UTC"
                   },
                   "image": [
                       {
                           "id": 4,
                           "image": "/storage/0f533d67ea524bb90e32ff80e410805c.jpg",
                           "created_at": {
                               "date": "2018-11-23 07:13:42.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ],
                   "category": [
                       {
                           "id": 2,
                           "name": "Garden",
                           "created_at": {
                               "date": "2018-11-23 07:13:30.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ]
               },
               {
                   "id": 5,
                   "name": "Fantastic Plastic Car",
                   "description": "CHAPTER XII. Alice's Evidence 'Here!' cried Alice, with a lobster as a partner!' cried the.",
                   "harga": 92512,
                   "stock": 19,
                   "created_at": {
                       "date": "2018-11-23 07:13:42.000000",
                       "timezone_type": 3,
                       "timezone": "UTC"
                   },
                   "image": [
                       {
                           "id": 5,
                           "image": "/storage/9384d20b6091bbfbfeb8302c79c6f72d.jpg",
                           "created_at": {
                               "date": "2018-11-23 07:13:44.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ],
                   "category": [
                       {
                           "id": 3,
                           "name": "Industrial",
                           "created_at": {
                               "date": "2018-11-23 07:13:30.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ]
               },
               {
                   "id": 6,
                   "name": "Incredible Paper Wallet",
                   "description": "I should be raving mad after all! I almost wish I hadn't drunk quite so much!' said Alice, 'I've.",
                   "harga": 76795,
                   "stock": 12,
                   "created_at": {
                       "date": "2018-11-23 07:13:42.000000",
                       "timezone_type": 3,
                       "timezone": "UTC"
                   },
                   "image": [
                       {
                           "id": 6,
                           "image": "/storage/cf37664ba111837293ef42e0c4cc9d3a.jpg",
                           "created_at": {
                               "date": "2018-11-23 07:13:47.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ],
                   "category": [
                       {
                           "id": 3,
                           "name": "Industrial",
                           "created_at": {
                               "date": "2018-11-23 07:13:30.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ]
               },
               {
                   "id": 7,
                   "name": "Aerodynamic Copper Gloves",
                   "description": "Gryphon, and all the way down one side and up the conversation a little. ''Tis so,' said Alice.",
                   "harga": 10486,
                   "stock": 10,
                   "created_at": {
                       "date": "2018-11-23 07:13:47.000000",
                       "timezone_type": 3,
                       "timezone": "UTC"
                   },
                   "image": [
                       {
                           "id": 7,
                           "image": "/storage/63c12a3a797f31b9df834beb5d874b85.jpg",
                           "created_at": {
                               "date": "2018-11-23 07:13:49.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ],
                   "category": [
                       {
                           "id": 4,
                           "name": "Grocery",
                           "created_at": {
                               "date": "2018-11-23 07:13:30.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ]
               },
               {
                   "id": 8,
                   "name": "Fantastic Steel Chair",
                   "description": "I mentioned before, And have grown most uncommonly fat; Yet you turned a corner, 'Oh my ears and.",
                   "harga": 96713,
                   "stock": 18,
                   "created_at": {
                       "date": "2018-11-23 07:13:47.000000",
                       "timezone_type": 3,
                       "timezone": "UTC"
                   },
                   "image": [
                       {
                           "id": 8,
                           "image": "/storage/d20a46ddea4dca7d680fb0474fdcb31e.jpg",
                           "created_at": {
                               "date": "2018-11-23 07:13:52.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ],
                   "category": [
                       {
                           "id": 4,
                           "name": "Grocery",
                           "created_at": {
                               "date": "2018-11-23 07:13:30.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ]
               },
               {
                   "id": 9,
                   "name": "Awesome Aluminum Wallet",
                   "description": "I am very tired of swimming about here, O Mouse!' (Alice thought this a very fine day!' said a.",
                   "harga": 25114,
                   "stock": 17,
                   "created_at": {
                       "date": "2018-11-23 07:13:52.000000",
                       "timezone_type": 3,
                       "timezone": "UTC"
                   },
                   "image": [
                       {
                           "id": 9,
                           "image": "/storage/bbd253ce75624cbe30c8db4cd6caea8b.jpg",
                           "created_at": {
                               "date": "2018-11-23 07:13:54.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ],
                   "category": [
                       {
                           "id": 5,
                           "name": "Shoes",
                           "created_at": {
                               "date": "2018-11-23 07:13:31.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ]
               },
               {
                   "id": 10,
                   "name": "Lightweight Paper Coat",
                   "description": "At last the Mouse, getting up and down looking for it, you know.' 'I don't see,' said the King.",
                   "harga": 24001,
                   "stock": 17,
                   "created_at": {
                       "date": "2018-11-23 07:13:52.000000",
                       "timezone_type": 3,
                       "timezone": "UTC"
                   },
                   "image": [
                       {
                           "id": 10,
                           "image": "/storage/f4878e40bd6f7234ea88ec2eac495b93.jpg",
                           "created_at": {
                               "date": "2018-11-23 07:13:57.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ],
                   "category": [
                       {
                           "id": 5,
                           "name": "Shoes",
                           "created_at": {
                               "date": "2018-11-23 07:13:31.000000",
                               "timezone_type": 3,
                               "timezone": "UTC"
                           }
                       }
                   ]
               }
           ],
           "meta": {
               "pagination": {
                   "total": 10,
                   "count": 10,
                   "per_page": 15,
                   "current_page": 1,
                   "total_pages": 1,
                   "links": []
               }
           }
       },
       "links": [
           {
               "rel": "category",
               "href": "http://inventory.lm.local/api/v1/category",
               "method": "GET"
           },
           {
               "rel": "self",
               "href": "http://inventory.lm.local/api/v1/product",
               "method": "GET"
           }
       ]
   }
```
```
   # Get product by ID
   GET /api/v1/product/{id}
   {
       "data": {
           "product": {
               "id": 1,
               "name": "Incredible Linen Pants",
               "description": "So she called softly after it, 'Mouse dear! Do come back again, and she could not even get her.",
               "harga": 52486,
               "stock": 14,
               "created_at": {
                   "date": "2018-11-23 07:13:31.000000",
                   "timezone_type": 3,
                   "timezone": "UTC"
               },
               "image": [
                   {
                       "id": 1,
                       "image": "/storage/0f8f1059b8e8ee2a5a46e76983fbf46f.jpg",
                       "created_at": {
                           "date": "2018-11-23 07:13:33.000000",
                           "timezone_type": 3,
                           "timezone": "UTC"
                       }
                   }
               ],
               "category": [
                   {
                       "id": 1,
                       "name": "Automotive",
                       "created_at": {
                           "date": "2018-11-23 07:13:30.000000",
                           "timezone_type": 3,
                           "timezone": "UTC"
                       }
                   }
               ]
           }
       }
   }
```

## Features 

**Services** implemented to facilitate work:

    -Api helpers function
    -Response method to manage error/success/exceptions responses
    -Log method to manage file log
    -Cache implements methods to manage File and Redis cache with serialization
    
**Roles and Permissions** to assign them to users and manage routes with greater security;

**Repository pattern** implemented to manage the models in an abstract way and to allow the scalability of the business logic (used to guarantee also the code cleaning)

**Transformer** classes to manipulate data and better manage the recovery of related information (are transformed through functions implemented in ApiService)
  
**Artisan commands** to create Repository, ApiController, Provider and Transoformers (Other commands to create example file view documentation)

## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
