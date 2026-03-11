    </div><!-- /.dashboard-wrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const sidebar=document.getElementById("sidebar"),overlay=document.getElementById("sidebarOverlay"),toggleBtn=document.getElementById("sidebarToggle");
if(toggleBtn){toggleBtn.addEventListener("click",()=>{sidebar.classList.toggle("open");overlay.classList.toggle("show");});}
if(overlay){overlay.addEventListener("click",()=>{sidebar.classList.remove("open");overlay.classList.remove("show");});}
setTimeout(()=>{document.querySelectorAll(".auto-dismiss").forEach(el=>{try{new bootstrap.Alert(el).close();}catch(e){}});},4000);
</script>
</body>
</html>
