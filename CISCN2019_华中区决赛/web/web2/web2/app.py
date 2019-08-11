from flask import Flask
import random 
from datetime import datetime

# init app
app = Flask(__name__)
app.secret_key = ''.join(random.choice("il1I|") for i in range(40))
print(app.secret_key)

from flask import Response
from flask import request, session
from flask import redirect, url_for, safe_join, abort
from flask import render_template_string

from data import data
post_storage = data
site_title = "A Flask Message Board"
site_description="Just leave what you want to say."

def render_template(filename, **args):
    with open(safe_join(app.template_folder, filename)) as f:
        template = f.read()
    name = session.get('name', 'anonymous')[:10]
    return render_template_string(template.format(remembered_name=name,site_description=site_description,site_title=site_title), **args)

@app.route('/')
def index():
    session['admin'] = session.get('admin',False)
    return render_template('index.html', posts = post_storage)

@app.route('/post', methods=['POST'])
def add_post():
    title = request.form.get('title', '[no title]')
    content = request.form.get('content', '[no content]')
    name = request.form.get('author', 'anonymous')[:10]
    
    post_storage.append({'title':title,'content':content,'author':name,'date':datetime.now().strftime("%B %d, %Y %X")})
    session['name'] = name
    return redirect('/')
    
@app.route('/admin',methods=['GET','POST'])
def admin():
    global site_description,site_title
    if session.get('admin',False):
        print('admin session.')
        if request.method == 'POST':
            if request.form.get('site_description'):
                site_description = request.form.get('site_description')
            if request.form.get('site_title'):
                site_title = request.form.get('site_title')
        return render_template('admin.html')    
    else:
        return "Not a admin **session**. <a href='/'>Back</a>"
    
@app.route('/s0urce')
def get_source():
    with open(__file__, "r") as f:
        resp = f.read()
    return Response(resp, mimetype="text/plain")
