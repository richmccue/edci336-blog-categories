# edci336-blog-categories

## How to Run
First clone the repo and then cd into this folder

### In Docker
First build the docker container with:
```bash
docker build -t edci336-blog-categories .
```

Then run the container with:
```bash
docker run -p 8000:80 edci336-blog-categories
```

Finally navigate to [http://localhost:8000](http://localhost:8000) and you will see the table

### On Machine
Make sure you have installed Python 3 and pip3 before running the commands below,

Then run the below command to get all the dependancies for the script:
```bash
pip install --no-cache-dir -r requirements.txt
```

After that runs, you can run the script with:
```bash
python3 blog-report.py
```

Once that is completed there should be an output.html file which contains the table!
