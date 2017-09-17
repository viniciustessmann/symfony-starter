<?php

namespace AppBundle\Service;

class UploadService
{
	public function uploadFile($entity)
	{
		// the file property can be empty if the field is not required
	    if (null === $entity->getFile()) {
	        return;
	    }

	    // use the original file name here but you should
	    // sanitize it at least to avoid any security issues

	    // move takes the target directory and then the
	    // target filename to move to

	    
	    $entity->getFile()->move(
	        $entity->getUploadRootDir(),
	        $entity->getFile()->getClientOriginalName()
	    );

	    // set the path property to the filename where you've saved the file
	    $entity->path = $entity->getFile()->getClientOriginalName();

	    
	    // clean up the file property as you won't need it anymore
	    $entity->setFile = null;
	}
}
