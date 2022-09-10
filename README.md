# PHP-Blog
PHP-Blog with extensive use of sessions
# Database structure
DBNAME Blog

## TABLES
### user 
    id
    firstName
    lastName
    username
    email
    passwordHash
    registeredAt
    profile

### post
    id
    authorId
    title
    createdAt
    updatedAt
    content

### reactions
    id
    postId
    reactorId
    subscribers

### postviews
    id
    postId
    views
