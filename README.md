![A80-cli GitHub Logo](https://user-images.githubusercontent.com/1971953/222934954-e0891cca-79ac-4b02-a330-0e5009968c9f.png)

# A80-cli

A80-cli is a simple PHP CLI app in which I will collect the tools that I commonly use for my work and that I currently
have in some private repositories or in personal workspace.

I'm going to collect (and convert) all my tools in this small application written

with [Laravel Zero](https://github.com/laravel-zero/laravel-zero).

It is currently in the preliminary stage.

In addition to the usual tools, I will add new ones to interact with **OpenAI** and more.

## Work progress

In this phase I am preparing the project in Laravel-Zero to accommodate the various tools.

I had the opportunity to experiment with different aspects of this fantastic project by Nuno Maduro and I find it
fantastic and fit for purpose.

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
- ai:image => generate an image from a prompt with the help of OpenAI
- ai:audio-to-text => transcribe an audio/video file to text in many languages with the help of OpenAI

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

### If you don't meet the requirements

If your development environment doesn't meet the requirements, you can use my Docker-based environment:

https://github.com/mirchaemanuel/ryuudev

Contains everything needed to develop and run `a80-cli`.

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

# Release History

<table>
    <thead>
    <tr>
        <th>Release</th>
        <th>Date</th>
        <th>Notes</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>0.1</td>
        <td>2023-01-14</td>
        <td>
            <ul>
                <li>Initial release</li>
                <li>OpenAI client library</li>
                <li>ai:query</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>0.1.3</td>
        <td>2023-01-14</td>
        <td>
            <ul>
                <li>tools:image:thumb</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>0.1.4</td>
        <td>2023-01-14</td>
        <td>
            <ul>
                <li>tools:image:list</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>0.1.5</td>
        <td>2023-01-14</td>
        <td>
            <ul>
                <li>tools:image:list improved with thumbnail generation and report</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>0.1.6</td>
        <td>2023-01-15</td>
        <td>
            <ul>
                <li>OpenAIService</li>
                <li>ai:title:abstract generate title and abstract of text file</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>0.1.7</td>
        <td>2023-01-20</td>
        <td>
            <ul>
                <li>added VERSION.yml</li>
                <li>introduced Storage disk &#039;app&#039; for local storage of preferences, data and cache</li>
                <li>added two new commands: check and env:create</li>
                <li>added database support for local storage</li>
                <li>created settings table</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>0.1.8</td>
        <td>2023-01-23</td>
        <td>
            <ul>
                <li>added ServiceProvider: AIServiceProvider and ImageServiceProvider</li>
                <li>added GD failover for image processing when Imagick is not available</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>0.1.9</td>
        <td>2023-02-07</td>
        <td>
            <ul>
                <li>fixed ImageUtils reference error</li>
                <li>added some useful warning to tools:image:list command</li>
                <li>converted VERSION.yml to JSON format for better compatibility</li>
                <li>added new commands: tools:yaml2json and tools:json2yaml</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>0.2</td>
        <td>2023-02-10</td>
        <td>
            <ul>
               <li>Introduced `Intervention Image` library for image processing</li>
               <li>Refactoring ImageService</li>
               <li>Added new command: tools:image:exif to read EXIF data from image file</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>0.3</td>
        <td>2023-03-04</td>
        <td>
            <ul>
               <li>Added new command: `ai:image` to generate image file from prompt with OpenAI</li>
               <li>Added new command: `ai:audio-to-text` to generate text from audio file with OpenAI</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td>0.3.1</td>
        <td>2023-03-05</td>
        <td>
            <ul>
                <li>[bug] image exif - Resolve issue with reading EXIF metadata from some images</li>
                <li>[bug] ai audio2text: resolve audio file access from inside the phar</li>
                <li>renamed `audio-to-text` to `audio2text`</li>
            </ul>
        </td>
    </tr>
    </tbody>
</table>
