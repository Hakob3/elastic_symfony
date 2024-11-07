# Article Management with Elasticsearch Integration

## Project Overview

This project demonstrates advanced setup, indexing, and search functionality with Elasticsearch through a system for managing articles and article categories. It is containerized using Docker and Docker Compose, and utilizes the `elasticsearch/elasticsearch` SDK for seamless integration with PHP.

### Key Components

1. **ElasticsearchFacade** — a service providing Elasticsearch functionality for Symfony entities, supporting indexing and search operations.
2. **ClientBuilder** — configures and initializes the Elasticsearch client for the application.

### Index Configuration with DTOs and Transformers

To enable flexible index configuration, the project uses DTOs and transformers, which apply PHP attributes to configure analysis, mapping, and other index settings. The `IndexBodyGenerator` class generates required index configurations based on these DTO attributes, simplifying the management of indexing parameters.

#### Primary Attributes for Index Configuration:

- **IndexingEntity** — this attribute is applied to the DTO transformer class and defines the related entity that will be indexed in Elasticsearch. Each `IndexingEntity` connects one entity with one DTO.
- **AnalysisSettings** — configures analysis settings for the index, corresponding to `{"settings": {"analysis": {...}}}` in Elasticsearch.
- **Mapping** — specifies field mappings, used directly on DTO properties. This aligns with Elasticsearch’s `{"mappings": {"properties": {...}}}`, enabling a flexible index structure.

To synchronize data with Elasticsearch, event listeners (postPersist, postUpdate, postRemove) automatically handle document updates in Elasticsearch whenever objects are created, updated, or deleted in the database.

## Deployment

### Setup and Run

The system utilizes Docker to simplify setup and deployment. To build and start the project, run the following command:

```bash
make build
```

This command performs the following actions:

- Builds and starts all containers.
- Installs dependencies via composer install.
- Configures the database and loads sample data (article fixtures) for demonstration.

## Admin Panel for Article and Category Management
EasyAdmin provides a user-friendly interface for managing entities:

- CRUD for Articles — add, edit, delete, and view articles.
- CRUD for Categories — manage article categories.
- Article Search via Elasticsearch — integrates search functionality with ElasticsearchFacade to enable article searches within Elasticsearch.

Additionally, an API route is available for asynchronous article searches using Elasticsearch at /api/search_article.