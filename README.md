# PHP-Blog
### PHP-Blog with extensive use of sessions
### Sending Emails
### Tracking views and comments


# Database structure
Database name 'Blog'

# TABLES
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
