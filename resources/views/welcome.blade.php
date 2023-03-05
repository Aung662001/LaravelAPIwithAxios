
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel Api Axous</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body class="pt-5" style="position:relative">
     {{-- create success alert --}}
     <div style="display: none" id="successMsg"class=" alert alert-success alert-dismissible fade show" role="alert">
        <strong>Great!</strong> Create Successful
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      {{-- delete success alete --}}
      <div style="display: none" id="deleteMsg"class=" alert alert-danger alert-dismissible fade show" role="alert">
         Delete Successful this item!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      {{--  --}}
    <div class="container">
        <div class="row">
            <div class="col-md-8 pt-3">
                <h2>Post</h2>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                  
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
               
                <h4>Create Post</h4>
                <form id="addForm">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-4 w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
   <!-- Modal for update data -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel"> Edit Post</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="editForm">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control updTitle" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control updDesc" required></textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Update</button>
                </div>
              </form>
        </div>
      </div>
    </div>
  </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        //Read
        const tableBody = document.getElementById("tableBody");
      const rerender =() =>{
        tableBody.innerHTML = ""
        axios.get("api/post")
        .then((response)=>{
          response.data.forEach(element => {
                const trow = document.createElement("tr")
              const html=`
              <td>${element.id}</td>
              <td>${element.title}</td>
              <td>${element.description}</td>
              <td>
                                <button 
                                class="btn btn-success btn-sm "
                                 data-bs-toggle="modal" 
                                 data-bs-target="#exampleModal"
                                 onclick=editBtn(${element.id})>
                                 Edit
                                 </button>
                                <button class="btn btn-danger btn-sm"
                                onclick=deleteBtn(${element.id})>Delete</button>
                            </td>
              `  
              trow.innerHTML=html
              tableBody.append(trow) ;
            });
           
        })
        .catch(error=>console.log(error.message))
      }
      rerender();
        //end Read
        //Create 
        
        const myForm = document.forms['addForm'];
        let title = myForm.title;
        let description = myForm.description;
      
        myForm.onsubmit=(e)=>{
            e.preventDefault()
            axios.post("/api/post",{
                title:title.value,
                description:description.value,
              
            }).then(response=>{
                console.log(response.data);
                const successMsg = document.getElementById("successMsg");
                successMsg.style.display="block"
                successMsg.style.position = "absolute"
                //add to ui
               rerender();
              //end add to ui
                setTimeout(()=>{
                        successMsg.style.display = "none"
                },3000)
                myForm.reset()
            })
        }
        //end create section
        //update section
        //show section
        const updTitle = document.querySelector(".updTitle");
        const updDesc = document.querySelector(".updDesc");
        let form = document.forms['editForm'];
        let modal = document.querySelector(".modal");
        let postIdToUpdate ;
        const editBtn = (postId) =>{
            postIdToUpdate = postId;
            ///use show route in PostController.php
            axios.get(`/api/post/${postId}`,()=>{
            }).then(response=>{
          form.title.value = response.data.title;
          form.description.value = response.data.description;
            })
            .catch((err)=>{
                console.log(err.message)
            })

        }
        //end show section
        form.onsubmit= (e)=>{
                e.preventDefault()
                console.log(title.value);
                 axios.put(`/api/post/${postIdToUpdate}`,{
                    title:form.title.value,
                    description:form.description.value
                 }).then(()=>{
                    console.log("success");
                    rerender();
                 }).catch(()=>{
                    console.log("error");
                 })
        }
        ///end update section
        ///delete section
   let deleteBtn = (id) =>{
     axios.delete(`api/post/${id}`)
     .then(()=>{
        const deleteMsg = document.getElementById("deleteMsg");
        deleteMsg.style.display="block";
        rerender();
        setTimeout(() => {
            deleteMsg.style.display="none";
        }, 3000);
     })
     .catch(err=>console.log("error in delete",err.message))
   }
        //end delete section
    </script>




</body>
</html>