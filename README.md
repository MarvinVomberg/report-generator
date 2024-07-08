# Report Generator
### A simple report generator for apprenticeships

This is a simple report generator for apprenticeships. It uses the OpenAI API to generate content for your reports. The reports are generated from a template PDF file. The fields in the PDF file are filled with the generated content.

## Installation
### Make sure to copy the .env.example file to .env and fill in the necessary information.
```bash
cp .env.example .env
```

### Also replace `storage/template.pdf` with your own template PDF file.

To know the field names of your PDF file, you can use the following command 
```bash
php application pdf:fields
```

## Usage
Start up with docker-compose
```bash
docker-compose up
```

```bash
php application generate
```

### Generated files will be stored in the `storage/filled` directory.

|Environment key  | Usage                                                                        |
|--|------------------------------------------------------------------------------|
|OPENAI_API_KEY| insert your private openai key                                               |
|OPENAI_INITIAL_PROMPT  | the initial prompt given to chatgpt as a system prompt                       |
|OPENAI_PROMPT_PREFIX    | Prefix for your entry, e.g. "The topic is: "                                 |
|META_YEAR  | The year of your apprenticeship                                              |
|META_COMPANY| The company name you work for                                                |
|META_NAME  | The apprentice name                                                          |
|PDF_COMPANY_FIELD_NAME| How the company name field is named in the pdf                               |
|PDF_YEAR_FIELD_NAME  | How the year field is named in the pdf                                       |
|PDF_FROM_DATE_FIELD_NAME| How the from date field is named in the pdf                                  |
|PDF_TO_DATE_FIELD_NAME  | How the to date field is named in the pdf                                    |
|PDF_SEQUENTIAL_NUMBER_FIELD_NAME| How the field is named that includes the ascending number of your reports    |
|PDF_COMPANY_WORK_FIELD_NAME| The field name where chat gpt generates content for                          |
|PDF_SCHOOL_WORK_FIELD_NAME  | The field where your school topics find place, you will be prompted for them |


## License

This project is an open-source software licensed under the MIT license.

Thanks to Nuno Maduro for Laravel Zero!
