# Order Microservice Lumen

## Endpoints

API JSON Responses.
```

  # Add item to cart (status field by default is 'incomplete')
  POST /api/v1/cart/
  formData : {
    "total":1200000,
    "product_id":1,
    "user_id":1,
    "stock":1
  }

  # Update item in cart
  POST /api/v1/cart/update/{id}
  formData : {
    "stock":12
  }

  # Delete item in cart
  GET /api/v1/cart/delete/{id}

  # Get all item in cart
  GET /api/v1/trolley/{user_id}

  # Show detail item in cart
  GET /api/v1/cart/{id}


  # All Invoice
  GET /api/v1/invoice/history/{user_id}

  # Show Invoice
  GET /api/v1/invoice/{id}


  # Checkout
  GET /api/v1/book/checkout/{user_id}

  # Commit
  POST /api/v1/book/commit/{user_id}
  formData : {
    "address":"Street no. 1A",
    "method":"internet banking"
  }

   
```

**JSON Output** Sample
```

  # Add item to cart (status field by default is 'incomplete')
  POST /api/v1/cart/
  {
    "success": "Cart created"
  }

  # Update item in cart
  POST /api/v1/cart/update/{id}
  {
    "success": "Cart updated"
  }

  # Delete item in cart
  GET /api/v1/cart/delete/{id}
  {
    "success": "Cart deleted"
  }

  # Get all item in cart
  GET /api/v1/trolley/{user_id}
  {
    "data": {
      "cart": [
        {
          "id": 4,
          "created_at": "2018-12-04 06:35:27",
          "total": 1200000,
          "status": "incomplete",
          "product_id": 2,
          "user_id": 2,
          "stock": 1,
          "invoice_id": null
        },
        {
          "id": 5,
          "created_at": "2018-12-04 06:39:01",
          "total": 1200000,
          "status": "incomplete",
          "product_id": 3,
          "user_id": 2,
          "stock": 1,
          "invoice_id": null
        }
      ]
    },
    "links": [
      {
        "rel": "self",
        "href": "http://order.lm.local/api/v1/cart",
        "method": "GET"
      }
    ]
  }

  # Show detail item in cart
  GET /api/v1/cart/{id}
  {
    "data": {
      "cart": {
        "id": 4,
        "created_at": "2018-12-04 06:35:27",
        "total": 1200000,
        "status": "incomplete",
        "product_id": 2,
        "user_id": 2,
        "stock": 1,
        "invoice_id": null
      }
    }
  }


  # All Invoice
  GET /api/v1/invoice/history/{user_id}
  {
    "data": {
      "invoice": [
        {
          "id": 2,
          "total": 1200000,
          "user_id": 2,
          "address": "Jl. Pasteur",
          "status": "lock",
          "method": "transfer",
          "created_at": "2018-12-04 01:26:06",
          "cart": [
            {
              "id": 2,
              "created_at": "2018-12-04 02:41:51",
              "total": 2000,
              "status": "pending",
              "product_id": 1,
              "user_id": 2,
              "stock": 1,
              "invoice_id": 2
            },
            {
              "id": 3,
              "created_at": "2018-12-04 02:43:31",
              "total": 1000000,
              "status": "pending",
              "product_id": 2,
              "user_id": 2,
              "stock": 1,
              "invoice_id": 2
            }
          ]
        },
        {
          "id": 4,
          "total": 0,
          "user_id": 2,
          "address": "jl. gunung batu",
          "status": "open",
          "method": "transfer atm",
          "created_at": "2018-12-04 02:46:19",
          "cart": []
        }
      ],
      "meta": {
        "pagination": {
          "total": 2,
          "count": 2,
          "per_page": 15,
          "current_page": 1,
          "total_pages": 1,
          "links": []
        }
      }
    },
    "links": [
      {
        "rel": "cart",
        "href": "http://order.lm.local/api/v1/cart",
        "method": "GET"
      },
      {
        "rel": "self",
        "href": "http://order.lm.local/api/v1/invoice",
        "method": "GET"
      }
    ]
  }

  # Show Invoice
  GET /api/v1/invoice/{id}
  {
    "data": {
      "invoice": {
        "id": 2,
        "total": 4000,
        "user_id": 2,
        "address": "Jl. Pasteur",
        "status": "lock",
        "method": "transfer",
        "created_at": "2018-12-04 01:26:06",
        "cart": [
          {
            "id": 2,
            "created_at": "2018-12-04 02:41:51",
            "total": 2000,
            "status": "incomplete",
            "product_id": 1,
            "user_id": 2,
            "stock": 1,
            "invoice_id": 2
          },
          {
            "id": 3,
            "created_at": "2018-12-04 02:43:31",
            "total": 2000,
            "status": "incomplete",
            "product_id": 2,
            "user_id": 2,
            "stock": 1,
            "invoice_id": 2
          }
        ]
      }
    }
  }


  # Checkout
  GET /api/v1/book/checkout
  {
    "data": {
        "cart": [
            {
                "id": 1,
                "created_at": "2018-12-10 06:08:57",
                "price": 67728,
                "status": "incomplete",
                "product_id": 1,
                "user_id": 3,
                "qty": 1,
                "invoice_id": null,
                "product": [
                    {
                        "id": 1,
                        "name": "Ergonomic Linen Bottle",
                        "description": "Adventures, till she was coming to, but it makes me grow larger, I can say.' This was such a nice.",
                        "harga": 67728,
                        "stock": 20,
                        "created_at": {
                            "date": "2018-12-07 06:03:14.000000",
                            "timezone_type": 3,
                            "timezone": "UTC"
                        }
                    }
                ]
            },
            {
                "id": 2,
                "created_at": "2018-12-10 06:09:05",
                "price": 12814,
                "status": "incomplete",
                "product_id": 3,
                "user_id": 3,
                "qty": 2,
                "invoice_id": null,
                "product": [
                    {
                        "id": 3,
                        "name": "Sleek Wooden Pants",
                        "description": "Alice, thinking it was very likely true.) Down, down, down. Would the fall was over. However, when.",
                        "harga": 12814,
                        "stock": 16,
                        "created_at": {
                            "date": "2018-12-07 06:03:14.000000",
                            "timezone_type": 3,
                            "timezone": "UTC"
                        }
                    }
                ]
            }
        ],
        "user": {
            "id": 3,
            "email": "isai.wiza@example.org",
            "name": "Marisa Gerlach",
            "username": "christ43",
            "phone": "09876543",
            "address": "Street no. 3"
        }
    }
  }

  # Commit
  POST /api/v1/book/commit/{user_id}
  {
    "data": {
        "invoice": {
            "id": 1,
            "total": 93356,
            "user_id": 3,
            "address": "Street no. A",
            "status": "waiting payment",
            "method": "transfer atm",
            "created_at": "2018-12-10 06:37:21",
            "cart": [
                {
                    "id": 1,
                    "created_at": "2018-12-10 06:08:57",
                    "price": 67728,
                    "status": "pending",
                    "product_id": 1,
                    "user_id": 3,
                    "qty": 1,
                    "invoice_id": 1
                },
                {
                    "id": 2,
                    "created_at": "2018-12-10 06:09:05",
                    "price": 12814,
                    "status": "pending",
                    "product_id": 3,
                    "user_id": 3,
                    "qty": 2,
                    "invoice_id": 1
                }
            ]
        }
    }
  }

