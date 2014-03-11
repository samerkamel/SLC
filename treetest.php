
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<script src="js/jquery/jquery-2.0.3.min.js"></script> 
	<script type="text/javascript">
	  $(document).ready(function(){
      $("ul.tv") // Find all unordered lists with class of "tv"
          .find("li:last-child").addClass("tvil").end() // Apply class "TVIL aka TreeView Item - Last"
          .find("li.tvclosed[ul]").addClass("tvie").swapClass("tvil", "tvile").append("<div class=\"tvca\">").end()
          .find("li[ul]").not(".tvclosed").addClass("tvopen").addClass("tvic").swapClass("tvil", "tvilc").append("<div class=\"tvca\">").end()
          .find("li.tvclosed>div.tvca").toggle(
                  function(){ $(this).parent("li").swapClass("tvic", "tvie").swapClass("tvilc", "tvile").find(">ul").slideDown("normal"); },
                  function(){ $(this).parent("li").swapClass("tvic", "tvie").swapClass("tvilc", "tvile").find(">ul").slideUp("normal"); }
              ).end()
          .find("li.tvopen>div.tvca").toggle(
                  function(){ $(this).parent("li").swapClass("tvic", "tvie").swapClass("tvilc", "tvile").find(">ul").slideUp("normal"); },
                  function(){ $(this).parent("li").swapClass("tvic", "tvie").swapClass("tvilc", "tvile").find(">ul").slideDown("normal"); }
              ); 
	  	
			$("address.email").each(function(){
				$(this).html("<a href=\"mailto:" + this.title.replace(/( \[at\] )/g, "@").replace(/( \[(dot|period)\] )/g, ".").replace(/( \[dash\] )/g, "-") + "?subject=Question%20Regarding%20" + escape($("title").html()) + "\" title=\"Send " + $(this).html() + " an email regarding " + $("title").html() + "\">" + $(this).html() + "<\/a>");
				this.setAttribute("title", "");
			});
		});
		
		$.fn.swapClass = function(c1,c2) {
			return this.each(function() {
				if ($.hasWord(this, c1)) {
					$(this).removeClass(c1);
					$(this).addClass(c2);
				} else if ($.hasWord(this, c2)) {
					$(this).removeClass(c2);
					$(this).addClass(c1);
				}					
			});
		};
	</script>
	<style type="text/css">
		ul.tv, .tv ul { 
			padding: 0;
			margin: 0;
			list-style: none;
		}	

		.tv li { 
			position: relative;
			margin: 0;
			padding: 4px 0 3px 20px;
			z-index: 10;
		}
		
		div.tvca { /* Clickable Area */
			_background: #fff;
			_filter: alpha(opacity=0);
			/* 
				border: 1px solid #fdd; 
			*/ /* Useful for showing the hit area */
			height: 15px;
			width: 15px;
			position: absolute;
			top: 1px;
			left: -1px;
			_left: -21px; /* IE... damnit! */
			cursor: pointer;
			z-index: 50;
		}

  	.tv li, .tv .tvi /* Tree View Item */ { background: url(img/treeView/dotted/tvi.gif) 0 0 no-repeat; }
  		
  	.tv .tvic /* Tree View Item, Collapsable */ { background-image: url(img/treeView/dotted/tvic.gif); }
  	.tv .tvie /* Tree View Item, Expandable */ { background-image: url(img/treeView/dotted/tvie.gif); }

  	.tv .tvil /* Tree View Last Item */ { background-image: url(img/treeView/dotted/tvil.gif); }
  	.tv .tvilc /* Tree View Last Item, Collapsable */ { background-image: url(img/treeView/dotted/tvilc.gif); }
  	.tv .tvile /* Tree View Last Item, Expandable */ { background-image: url(img/treeView/dotted/tvile.gif); }

  	.tvload /* Loading Icon */ { background-image: url(img/treeView/dotted/tviload.gif); }
		
		.tvclosed ul,
		.tvclosed li.tvclosed ul	{
			display: none;
		}
		.tvclosed ul ul {
			display: block;
		}

	</style>
</head>
<body>


	<h2>Sample Markup</h2>
	<h3>Hard Coded HTML</h3>
	<h4>Sample 1</h4>
	<ul class="tv">
		<li class="tvclosed">Item 1 - Closed
			<ul>
				<li>Item 1.1</li>
			</ul>
		</li>
		<li>
			Item 2
			<ul>
				<li>
					Item 2.1
					<ul>
						<li>Item 2.1.1</li>
						<li>Item 2.1.2</li>
					</ul>
				</li>
				<li>Item 2.2</li>
			</ul>
		</li>
		<li>Item 3</li>
	</ul>
	
	<h4>Sample 2</h4>
	<ul class="tv">
		<li>Item 1</li>
		<li class="tvclosed">
			Item 2 - Closed
			<ul>
				<li>
					Item 2.1
					<ul>
						<li>Item 2.1.1</li>
						<li>Item 2.1.2</li>
					</ul>
				</li>
				<li>Item 2.2</li>
				<li>
					Item 2.3
					<ul>
						<li>Item 2.3.1</li>
						<li>Item 2.3.2</li>
					</ul>
				</li>
			</ul>
		</li>
	</ul>	

</body>
</html>                                                            