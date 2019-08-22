<?php
class faIO
{
	function create_directory($directory, $chmod = 0700)
	{
		if (file_exists($directory) == false)
		{
			mkdir($directory, $chmod);
		};
	}
	
	
	function read_directory($directory)
	{
		$result = array();
		if ($handle = opendir($directory))
		{
			while (false !== ($entry = readdir($handle)))
			{
				if ($entry != "." && $entry != "..")
				{
					array_push($result, $entry);
				};
			};
			closedir($handle);
		};
		return $result;
	}
	
	function read_files($directory, $extension)
	{
		$result = array();
		if ($handle = opendir($directory))
		{
			while (false !== ($entry = readdir($handle)))
			{
				if ($entry != "." && $entry != "..")
				{
					if (strpos($entry, $extension) == true)
					{
						array_push($result, $entry);
					};
				};
			};
			closedir($handle);
		};
		return $result;
	}
}
?>