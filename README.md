<img src="https://user-images.githubusercontent.com/1971953/212487575-3d285e97-1aba-418d-8e5d-69e4caa39ec5.png" alt="a80-cli logo" height="200"/>

# A80-cli

A80-cli is a simple PHP CLI app in which I will collect the tools that I commonly use for my work and that I currently have in some private repositories or in personal workspace.

I'm going to collect (and convert) all my tools in this small application written with [Laravel Zero](https://github.com/laravel-zero/laravel-zero).

It is currently in the preliminary stage.

In addition to the usual tools, I will add new ones to interact with **OpenAI** and more.

## Work progress

In this phase I am preparing the project in Laravel-Zero to accommodate the various tools.

I had the opportunity to experiment with different aspects of this fantastic project by Nuno Maduro and I find it fantastic and fit for purpose.

## Coming soon

- scripts/tools that I currently use to manipulate and collect my photos
- report generators
- web tools
    - crawler and scraper
    - sitemap generator
- ...

## OpenAI

Currently there is some command to interact with OpenAI
- ai:query => allow the user to ask question to OpenAI
- ai:title-abstract => generate a SEO title and an abstract from a text file (plain text only)

```
./a80 ai:query --help

Description:
  place a question to OpenAI (using da-vinci)

Usage:
  ai:query [options] [--] [<question>]

Arguments:
  question                       question to ask

Options:
  -m, --max-tokens[=MAX-TOKENS]  [1-4000] default is 2000
  
./a80 ai:title-abstract --help                                                                             ─╯
Description:
  Generate a title and description from a text file

Usage:
  ai:title-abstract [options] [--] <filename>

Arguments:
  filename                       file to read

Options:
  -m, --max-tokens[=MAX-TOKENS]  [1-4000] default is 2000
      --model[=MODEL]            [davinci, ada, babbage, curie, content-filter-alpha-c4] default is davinci

```

## Requirements

- PHP 8.1+
- Imagick or GD extension for image processing
- SQLite extension for database
- Yaml parser (suggested)

### Check requirements

You can execute a command to check requirement:

```
./a80 check

Checking system preconditions and requirements...
 - creating app settings path: /home/user/.a80_cli/: ✔
 - .env file: ✔
 - yaml extension: ✔
 - imagick extension: ✔
 - GD extension: ✔
 - sqlite extension: ✔
```

## Usage

### Installing composer dependencies

```
composer install
```

or

```
docker run --rm --interactive --tty --volume $PWD:/app composer install --ignore-platform-reqs
```

### Build

You can build the application

```
php a80 app:build
```

It will create a phar executable archive in `/builds`

