var db = connect("mongodb://localhost/admin");

db.createUser(
  {
    user: "dev",
    pwd: "secret",
    roles: [ { role: "admin", db: "admin" } ]
  }
)